<?php
namespace Tests\Unit\Controllers;

use function Ipblocker\Functions\cp;

use Tests\Unit\BaseTest;
use Ipblocker\Controllers\ControllerMain;

class ControllerMainTest extends BaseTest
{
    private const IP_BANNED = "5.188.84.59";
    private const IP_VALID = "176.83.68.84";

    public function _test_empty_request()
    {
        $serv = new RulezChecker();
        $r = $serv->is_forbidden();
        $this->assertFalse($r);
    }



}