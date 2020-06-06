<?php
namespace TheFramework\Providers;

use TheFramework\Components\ComponentSearchbots as sb;
use TheFramework\Components\ComponentConfig as cfg;
use TheFramework\Components\Db\ComponentMysql;
use Theframework\Traits\TraitLog;

class ProviderBase
{
    use TraitLog;

    private $db;
    private $remoteip;

    public function __construct($remoteip)
    {
        $dbname = $this->_get_dbname_by_env();
        $config = cfg::get_schema("c1", $dbname);
        $this->db = new ComponentMysql($config);
        $this->remoteip = $remoteip;
    }

    private function _get_dbname_by_env()
    {
        $env = cfg::get_env();
        if($env=="prod")
            return "dbs433062";
        return "db_security";
    }

    public function is_blacklisted()
    {
        $sql = "
        -- is_blacklisted
        SELECT id FROM app_ip_blacklist WHERE remote_ip='$this->remoteip' AND is_blocked=1";
        $id = $this->db->query($sql,0,0);
        //print_r($id);
        return $id;
    }

    public function is_registered()
    {
        $sql = "
        -- is_registered
        SELECT id FROM app_ip WHERE remote_ip='$this->remoteip'";
        $id = $this->db->query($sql,0,0);
        return $id;
    }

    private function get_searchbot()
    {
        return sb::get_name($this->remoteip);
    }

    private function save_app_ip()
    {
        $sql = "
        -- save_app_ip 
        INSERT INTO app_ip (remote_ip) VALUES('$this->remoteip')";

        $searchbot = $this->get_searchbot();
        if($searchbot)
            $sql = "
            -- save_app_ip 
            INSERT INTO app_ip (remote_ip, whois) VALUES('$this->remoteip','host:$searchbot')";

        $this->db->exec($sql);
    }

    private function to_json($arvar)
    {
        if($arvar) {
            $string = json_encode($arvar);
            //$string = serialize($string);    # safe -- won't count the slash
            //return addslashes($string);
            $string = str_replace("'","\\'",$string);
            return $string;
        }
        return "";
    }

    public function save_request()
    {
        if(!$this->is_registered()) $this->save_app_ip();

        $requesturi = addslashes($_SERVER["REQUEST_URI"] ?? "no-req-uri");
        $domain = $_SERVER['HTTP_HOST'] ?? "no-req-domain";
        $get = $this->to_json($_GET);
        $post = $_POST;
        if(isset($post["password"])) $post["password"] = "****";
        $post = $this->to_json($post);
        $files = $this->to_json($_FILES);

        $sql = "
        -- save_request
        INSERT INTO app_ip_request (`remote_ip`,`domain`,`request_uri`,`post`,`get`,`files`) 
        VALUES ('$this->remoteip','$domain','$requesturi','$post','$get','$files')";
        $this->db->exec($sql);
    }

    private function _is_and($arwords,$strcontent)
    {
        foreach ($arwords as $w)
            if(!strstr($strcontent,$w))
                return false;
        return true;
    }

    private function _is_andkeywords($strcontent,$method)
    {
        //print_r($strcontent);die;
        $keywordsand = cfg::get_keywords("and",$method);
        foreach($keywordsand as $arkw)
            if($this->_is_and($arkw,$strcontent))
                return implode(",",$arkw);
        return false;
    }

    private function _is_orkeywords($strcontent,$method)
    {
        $keywordsor = cfg::get_keywords("or",$method);
        //print_r($keywordsor);die;
        foreach($keywordsor as $kw)
            if(strstr($strcontent,$kw))
                return $kw;
        return false;
    }

    private function _get_json_ofmethod($method="post")
    {
        if($method=="post") {
            $post = $_POST;
            unset($post["password"]);
            $json = $this->to_json($post);
        }
        else
            $json = $this->to_json($_GET);
        $json = strtolower($json);
        $json = str_replace("\/","/",$json);
        return $json;
    }

    private function _is_method_nok($method="post")
    {
        if($method=="post" && !$_POST) return "";
        if($method=="get" && !$_GET) return "";

        $methodjson = $this->_get_json_ofmethod($method);
        if($_POST && $this->_is_unicode($methodjson))
            return "unicode";

        $isorkw = $this->_is_orkeywords($methodjson, $method);
        if($isorkw)
            return "or {$method}:$isorkw";

        $isandkw = $this->_is_andkeywords($methodjson, $method);
        if($isandkw)
            return "and {$method}:$isandkw";

        return "";
    }

    private function _is_unicode($string)
    {
        return (strlen($string) != strlen(utf8_decode($string)));
    }

    public function get_forbidden_words()
    {

        //comprueba si todo va bien en post
        $isnok = $this->_is_method_nok();
        if($isnok) return $isnok;
        $isnok = $this->_is_method_nok("get");
        if($isnok) return $isnok;
        return "";
    }

    public function add_to_blacklist($kws)
    {
        $sql = "
        -- add_to_blacklist
        INSERT INTO app_ip_blacklist (`remote_ip`,`reason`) 
        VALUES ('$this->remoteip','keywords: $kws')";
        $this->db->exec($sql);
    }
}