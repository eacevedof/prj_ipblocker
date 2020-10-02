<?php
// vendor/bin/phpunit ./tests/ExampleTest.php
namespace Tests\Unit\Components\Db;

use Tests\Unit\BaseTest;
use Ipblocker\Components\Db\MysqlComponent;

class MysqlComponentTest extends BaseTest
{
    public function test_connection()
    {
        $arconf = [
            "server"    => "127.0.0.1",
            "database"  => "db_security",
            "port"      => "3306",
            "user"      => "root",
            "password"  => "1234",
        ];

        $o = new MysqlComponent($arconf);
        $sql = "
        SELECT COUNT(*)
        FROM information_schema.tables 
        WHERE table_schema =  DATABASE()
        AND table_name =  'app_ip';
        ";
        $r = $o->query($sql);
        if($o->is_error()) $this->logd($o->get_errors(),"test_connection.errors");
        $this->assertEquals(false,$o->is_error());
    }

}