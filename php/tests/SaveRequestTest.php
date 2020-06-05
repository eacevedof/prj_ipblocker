<?php
include("BaseTest.php");
use Tests\BaseTest;
use \TheFramework\Components\ComponentIpblocker;

final class SaveRequestTest extends BaseTest
{

    public function __construct()
    {
        $_POST = [];
        $_FILES = [];
        $_GET = [];
    }

    private function pr()
    {
        print_r($_POST);
        print_r($_GET);
        print_r($_FILES);
    }

    public function run()
    {
        $o = new ComponentIpblocker();
        $o->handle_request();
    }
}

//otra forma es lanzar: php -S localhost:2000 -t public
if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
{
    $t = new SaveRequestTest();
    $t->run();
}