<?php
// vendor/bin/phpunit tests/Unit/Services
namespace Tests\Unit\Services;
use function Ipblocker\Functions\cp;

use Tests\Unit\BaseTest;
use Ipblocker\Services\RequestCheckService;

final class RequestCheckServiceTest extends BaseTest
{
    private const IP_BANNED = "127.0.0.B";
    private const IP_NOT_BANNED = "176.83.68.84";

    public function test_savefull_request()
    {
        $this->_reset_fullrequest();
        $this->_add_server("HTTP_HOST", "sometestdomain.com");
        $this->_add_server("REMOTE_ADDR", self::IP_NOT_BANNED);
        $this->_add_server("REQUEST_URI", "/some/request/uri");
        $this->_add_server("HTTP_USER_AGENT", "some-user agent");
        $this->_add_post("kp1","vp1");
        $this->_add_post("kp2","vp2");
        $this->_add_get("kg1","vg1");
        $this->_add_get("kg2","vg2");
        $this->_add_files("kF1","vF1");
        $this->_add_files("kF2","vF2");

        $r = (new RequestCheckService())->handle_request();
        $this->assertTrue($r);
    }

    public function test_not_banned_ip()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR", self::IP_NOT_BANNED);
        $r = (new RequestCheckService())->handle_request();
        $this->assertTrue($r);
    }

    /**
     * @runInSeparateProcess
     */
    public function est_banned_ip()
    {
        //aqui handle_request hace echo y no pasa
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR", self::IP_BANNED);
        $r = (new RequestCheckService())->handle_request();
        $this->assertEmpty($r);
    }
}