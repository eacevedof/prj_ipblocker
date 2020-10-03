<?php
// vendor/bin/phpunit ./tests/ExampleTest.php
namespace Tests\Unit\Components\Db;

use Tests\Unit\BaseTest;

class MysqlComponentTest extends BaseTest
{
    public function test_connection()
    {
        $db = $this->_get_db();
        $sql = "
        SELECT COUNT(*)
        FROM information_schema.tables 
        WHERE table_schema =  DATABASE()
        AND table_name =  'app_ip';
        ";
        $r = $db->query($sql);
        if($db->is_error()) $this->logd($db->get_errors(),"test_connection.errors");
        $this->assertEquals(false,$db->is_error());
    }

}