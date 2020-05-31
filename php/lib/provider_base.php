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

        $requesturi = addslashes($_SERVER["REQUEST_URI"]);
        $domain = $_SERVER['HTTP_HOST'];
        $get = $this->to_json($_GET);
        $post = $this->to_json($_POST);
        $files = $this->to_json($_FILES);

        $sql = "
        -- save_request
        INSERT INTO app_ip_request (`remote_ip`,`domain`,`request_uri`,`post`,`get`,`files`) 
        VALUES ('$this->remoteip','$domain','$requesturi','$post','$get','$files')";
        $this->db->exec($sql);
    }
}