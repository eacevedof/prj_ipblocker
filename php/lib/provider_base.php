<?php
namespace TheFramework\Providers;

use TheFramework\Components\ComponentSearchbots as sb;
use TheFramework\Components\ComponentConfig as cfg;
use TheFramework\Components\Db\ComponentMysql;
use Theframework\Traits\TraitLog;
use TheFramework\Helpers\HelperRequest as req;

class ProviderBase
{
    use TraitLog;

    private $req;
    private $db;
    private $remoteip;

    public function __construct()
    {
        $dbname = $this->_get_dbname_by_env();
        $config = cfg::get_schema("c1", $dbname);
        $this->db = new ComponentMysql($config);
        $this->req = req::getInstance();
        $this->remoteip = $this->req->get_remoteip();
    }

    private function _get_searchbot()
    {
        return sb::get_name($this->remoteip);
    }

    private function _get_dbname_by_env()
    {
        $env = cfg::get_env();
        if($env=="prod")
            return "dbs433062";
        return "db_security";
    }

    private function _save_app_ip()
    {
        $whois = $this->req->get_whois();
        $sql = "
        -- save_app_ip 1
        INSERT INTO app_ip (remote_ip, country, whois) VALUES('$this->remoteip','{$whois["country"]}','{$whois["whois"]}')";

        $searchbot = $this->_get_searchbot();
        if($searchbot)
            $sql = "
            -- save_app_ip 2
            INSERT INTO app_ip (remote_ip, country, whois) VALUES('$this->remoteip','{$whois["country"]}','host:$searchbot')";

        $this->db->exec($sql);
    }

    private function _to_json($arvar)
    {
        if($arvar) {
            $string = json_encode($arvar);
            $string = str_replace("'","\\'",$string);
            return $string;
        }
        return "";
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

    public function save_request()
    {
        if(!$this->is_registered()) $this->_save_app_ip();

        $requesturi = addslashes($this->req->get_requri());
        $domain = $this->req->get_domain();
        $get = $this->_to_json($_GET);
        $post = $_POST;
        if(isset($post["password"])) $post["password"] = "****";
        $post = $this->_to_json($post);
        $files = $this->_to_json($_FILES);

        $sql = "
        -- save_request
        INSERT INTO app_ip_request (`remote_ip`,`domain`,`request_uri`,`post`,`get`,`files`) 
        VALUES ('$this->remoteip','$domain','$requesturi','$post','$get','$files')";
        $this->db->exec($sql);
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