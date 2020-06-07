<?php
namespace Tests;

$pathboot = realpath(__DIR__."/../boot");
include("$pathboot/appbootstrap.php");

use Theframework\Traits\TraitLog;

abstract class BaseTest
{
    use TraitLog;

    public function __construct()
    {
        $_POST = [];
        $_FILES = [];
        $_GET = [];
        $_SERVER = [];
    }

    protected function log_globals()
    {
        $this->logd($_POST,"POST");
        $this->logd($_GET,"GET");
        $this->logd($_FILES,"FILES");
        $this->logd($_SERVER,"SERVER");
    }

    protected function add_post($k,$v)
    {
        if(is_string($k))  $_POST[$k] = $v;
        return $this;
    }

    protected function add_get($k,$v)
    {
        if(is_string($k)) $_GET[$k] = $v;
        return $this;
    }

    protected function reset_post()
    {
        $_POST = [];
        return $this;
    }

    protected function reset_get()
    {
        $_GET = [];
        return $this;
    }

    protected function add_server($k,$v)
    {
        if(is_string($k)) $_SERVER[$k] = $v;
        return $this;
    }

    protected function reset_all()
    {
        $_POST=[]; $_GET=[]; $_FILES=[]; $_SERVER = [];
        return $this;
    }

    public abstract function run();
}