<?php
namespace TheFramework\Providers;

use TheFramework\Components\ComponentConfig as cfg;
use TheFramework\Components\Db\ComponentMysql;

class ProviderBase
{
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

    private function save_app_ip()
    {
        $sql = "
        -- save_app_ip 
        INSERT INTO app_ip (remote_ip) VALUES('$this->remoteip')";
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
        $post = $this->to_json($_POST);
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

    private function _is_andkeywords($strcontent)
    {
        //print_r($strcontent);die;
        $keywordsand = [
            ["http://",".ru/"],
            [".ru\""],
            ["https://",".ru/"],
            ["http://"," sex "],
            ["https://"," sex "],
            ["http://"," offer "],
            ["https://"," offer "],
            ["http://","walmart.com"],
            ["https://","walmart.com"]
        ];
        foreach($keywordsand as $arkw)
            if($this->_is_and($arkw,$strcontent))
                return implode(",",$arkw);
        return false;
    }

    private function _is_orkeywords($strcontent)
    {
        $keywordsand = [".link/"," dating ","-sex "];
        foreach($keywordsand as $kw)
            if(strstr($strcontent,$kw))
                return $kw;
        return false;
    }

    public function get_forbidden_words()
    {
        //$sql = "SELECT word FROM app_keyword";
        //$keywords = $this->db->exec($sql);
        $postjson = $this->to_json($_POST);
        $postjson = strtolower($postjson);
        $postjson = str_replace("\/","/",$postjson);
        //print_r($postjson);die;
        $isandkw = $this->_is_andkeywords($postjson);
        $isorkw = $this->_is_orkeywords($postjson);
        if($isandkw || $isorkw)
            return "or:$isorkw, and:$isandkw";
        return "";
    }

    public function add_to_blacklist($kws)
    {
        $sql = "
        -- add_to_blacklist
        INSERT INTO app_ip_blacklist (`remote_ip`,`reason`) 
        VALUES ('$this->remoteip','by keywords=> $kws')";
        $this->db->exec($sql);
    }
}