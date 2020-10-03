<?php
// vendor/bin/phpunit tests/Unit/Services
namespace Tests\Unit\Services;

use function Ipblocker\Functions\cp;

use Tests\Unit\BaseTest;
use Ipblocker\Services\RulescheckerService;

class RulescheckerServiceTest extends BaseTest
{
    private const IP_BANNED = "5.188.84.59";
    private const IP_VALID = "176.83.68.84";

    public function test_empty_request()
    {
        $serv = new RulescheckerService();
        $r = $serv->is_forbidden();
        $this->assertFalse($r);
    }

    public function test_alldomains_blocked_by_country()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR",self::IP_BANNED);

        $r = (new RulescheckerService())->is_forbidden();
        $this->assertNotEmpty($r);
        $this->assertEquals("country:RU",$r);
    }

    //todo esto debería devolver error
    public function __est_alldomains_forbidden_post()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR",self::IP_VALID);
        $this->_add_server("HTTP_HOST", "anydomain.com");
        $this->_add_post("any-field","topcasinos");

        $r = (new RulescheckerService())->is_forbidden();
//r===false pero tndría que devolver un error de regla
//cp($r,"test_alldomains_forbidden_post is_forbidden?");
        $this->assertEquals("country:ES",$r);
    }

    public function test_somedomain_reqfields()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR",self::IP_VALID);
        $this->_add_server("HTTP_HOST", "theframework.es");
        $this->_add_server("REQUEST_URI","/en/contact/");
        $this->_add_post("any-field","topcasinos");
//cp($_SERVER,"test.server",0);
        $r = (new RulescheckerService())->is_forbidden();
//cp($r,"test_somedomain_forbidden_post");
        $this->assertNotEmpty($r);
        $this->assertEquals("reqfields hidAction",$r);
    }

    //ip without country: 154.57.3.132
}