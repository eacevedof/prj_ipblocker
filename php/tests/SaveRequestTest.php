<?php
include("BaseTest.php");
use Tests\BaseTest;

final class SaveRequestTest extends BaseTest
{

    public function __construct()
    {
        $_POST = [];
        $_FILES = [];
        $_GET = [];
    }

    public function run()
    {
        print_r($_POST);
    }
}

if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
{
    $t = new SaveRequestTest();
    $t->run();
}