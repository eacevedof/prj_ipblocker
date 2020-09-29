<?php
namespace App\Tests\Unit\Services;

use function Ipblocker\Functions\cp;

use App\Tests\Unit\BaseTest;
use Ipblocker\Services\RulezChecker;

class RulezCheckerTest extends BaseTest
{
    private const IP_BANNED = "5.188.84.59";
    private const IP_VALID = "176.83.68.84";

    public function test_empty_request()
    {
        $serv = new RulezChecker();
        $r = $serv->is_forbidden();
        $this->assertFalse($r);
    }

    public function test_alldomains_blocked_by_country()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR",self::IP_BANNED);

        $r = (new RulezChecker())->is_forbidden();
        $this->assertNotEmpty($r);
        $this->assertEquals("country:RU",$r);
    }

    //todo esto debería devolver error
    public function test_alldomains_forbidden_post()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR",self::IP_VALID);
        $this->_add_server("HTTP_HOST", "anydomain.com");
        $this->_add_post("any-field","topcasinos");

        $r = (new RulezChecker())->is_forbidden();
        //print_r($r);die;
        $this->assertNotEmpty($r);
        $this->assertEquals("country:ES",$r);
    }


}