<?php
namespace App\Tests\Unit\Services;

use App\Tests\Unit\BaseTest;
use Ipblocker\Services\RulezChecker;

class RulezCheckerTest extends BaseTest
{
    public function test_empty_request()
    {
        $serv = new RulezChecker();
        $r = $serv->is_forbidden();
        $this->assertFalse($r);
    }


}