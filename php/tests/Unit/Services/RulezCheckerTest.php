<?php
namespace App\Tests\Unit\Services;

use App\Tests\Unit\BaseTest;
use Ipblocker\Services\RulezChecker;

class RulezCheckerTest extends BaseTest
{
    private const IP_BANNED = "5.188.84.59";

    public function test_empty_request()
    {
        $serv = new RulezChecker();
        $r = $serv->is_forbidden();
        $this->assertFalse($r);
    }

    public function test_blocked_by_country()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR",self::IP_BANNED);

        $r = (new RulezChecker())->is_forbidden();
        $this->assertNotEmpty($r);
        $this->assertEquals("country:RU",$r);
    }

}