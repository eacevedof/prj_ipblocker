<?php
// vendor/bin/phpunit ./tests/ExampleTest.php
namespace App\Tests\Unit\Components\Db;

use App\Tests\Unit\BaseTest;
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
        //print_r("logs:".IPB_ENABLE_LOGS);die;
        //print_r($o->get_errors());die;
        $this->logd($o->get_errors(),"test_connection");
        $this->assertEquals(false,$o->is_error());
    }

}