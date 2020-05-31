<?php
namespace TheFramework\Providers;

use TheFramework\Components\ComponentConfig;
use TheFramework\Components\Db\ComponentMysql;

class ProviderBase
{
    private $db;

    public function __construct()
    {
        $config = ComponentConfig::get_schema("c1","db_security");
        print_r($config);
        $this->db = new ComponentMysql($config);
    }

    public function is_bocked($ip)
    {
        $sql = "SELECT ip FROM app_blacklist WHERE ip='$ip'";
        $result = $this->query($sql);
        print_r($result);
    }

    private function query($sql)
    {
        return $this->db->query($sql);
    }
}