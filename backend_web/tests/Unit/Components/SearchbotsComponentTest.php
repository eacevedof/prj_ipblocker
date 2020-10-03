<?php
// vendor/bin/phpunit tests/Unit/Components
namespace Tests\Unit\Components;

use function Ipblocker\Functions\cp;

use Tests\Unit\BaseTest;
use Ipblocker\Components\SearchbotsComponent as sb;

class SearchbotsComponentTest extends BaseTest
{
    private const IP_DUCKDUCK_GO_1 = "50.16.241.117";

    public function test_is_duckduckgo()
    {
        $name = sb::get_name(self::IP_DUCKDUCK_GO_1);
        $this->assertEquals("duckduckgo",$name);
    }

}