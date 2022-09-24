<?php
namespace Tests\Unit\Controllers;

use function Ipblocker\Functions\cp;
use Tests\Unit\BaseTest;
use Ipblocker\Controllers\ControllerMain;

final class ControllerMainTest extends BaseTest
{
    public function test_invocable()
    {
        $isinvocable = method_exists((new ControllerMain), "__invoke");
        $this->assertTrue($isinvocable);
    }
}