<?php
namespace TheFramework\Providers;

use TheFramework\Components\ComponentConfig;
use TheFramework\Components\Db\ComponentMysql;

class ProviderBase
{
    private $db;

    public function __construct()
    {
        $config = ComponentConfig::get_schema("ci","db_security");
        $this->db = new ComponentMysql();
    }
}