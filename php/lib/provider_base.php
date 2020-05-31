<?php
namespace TheFramework\Providers;

use TheFramework\Components\ComponentConfig;
use TheFramework\Components\Db\ComponentMysql;

class ProviderBase
{
    private $db;
    private $remoteip;

    public function __construct($remoteip)
    {
        print_r($remoteip);
        $config = ComponentConfig::get_schema("c1","db_security");
        $this->db = new ComponentMysql($config);
        $this->remoteip = $remoteip;
    }

    public function is_blacklisted()
    {
        $sql = "SELECT id FROM app_blacklist WHERE ip_remote='$this->remoteip' AND is_blocked=1 ";
        $id = $this->query($sql,0,0);
        return $id;
    }

    public function is_registered()
    {
        $sql = "SELECT id FROM app_ip WHERE ip_remote='$this->remoteip'";
        $id = $this->query($sql,0,0);
        return $id;
    }

    private function save_app_ip()
    {
        $sql = "INSERT INTO app_ip (remote_ip) VALUES('$this->remoteip')";
        print_r($sql);
        $this->db->exec($sql);
    }

    private function query($sql,$icol=null,$irow=null)
    {
        return $this->db->query($sql,$icol,$irow);
    }

    public function save_request()
    {
        if(!$this->is_registered()) $this->save_app_ip();

        $requesturi = $_SERVER["REQUEST_URI"];
        $domain = $_SERVER['HTTP_HOST'];
        $get = var_export($_GET, 1);
        $post = var_export($_POST,1);
        $files = var_export($_FILES,1);

        $sql = "INSERT INTO app_ip_request (remote_ip,domain,request_uri,post,get,files) VALUES ('$this->remoteip','$domain','$requesturi','$post','$get','$files')";
        $this->db->exec($sql);
    }
}