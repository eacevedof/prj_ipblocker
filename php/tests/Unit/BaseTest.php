<?php
declare(strict_types=1);
namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Ipblocker\Traits\LogTrait as Log;

use Ipblocker\Helpers\HelperRequest;
use Ipblocker\Component\ComponentIpblocker;

abstract class BaseTest extends TestCase
{
    use Log;

    public function setUp(): void
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
        HelperRequest::reset();
        unset($_POST,$_GET,$_FILES,$_SERVER);
        $_POST=[]; $_GET=[]; $_FILES=[]; $_SERVER = [];
        return $this;
    }

    protected function _execute_ipblocker($m)
    {
        echo "\n==================\n";
        echo "$m";
        echo "\n==================\n";
        (new ComponentIpblocker())->test_handle_request($m);
    }
}