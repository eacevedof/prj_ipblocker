<?php
// vendor/bin/phpunit tests/Unit/Components
namespace Tests\Unit\Components;

use function Ipblocker\Functions\cp;

use Tests\Unit\BaseTest;
use Ipblocker\Components\SearchbotsComponent as sb;

class SearchbotsComponentTest extends BaseTest
{
    private const IP_DUCKDUCK_GO_1 = "50.16.241.117";
    private const IP_GOOGLE_1 = "216.58.211.227";
    private const IP_GOOGLEBOT_1 = "66.249.79.210";
    private const IP_GOOGLEBOT_2 = "66.249.79.108";
    private const IP_YAHOO_1 = "212.82.100.150";


    public function test_is_duckduckgo()
    {
        $name = sb::get_name(self::IP_DUCKDUCK_GO_1);
        $this->assertEquals("duckduckgo",$name);
    }

    public function test_is_googlebot()
    {
        $name = sb::get_name(self::IP_GOOGLEBOT_1);
        $this->assertEquals("google2",$name);

        $name = sb::get_name(self::IP_GOOGLEBOT_2);
        $this->assertEquals("google2",$name);
    }

    public function test_is_yahoobot()
    {
        $name = sb::get_name(self::IP_YAHOO_1);
        $this->assertEquals("yahoo2",$name);
    }

}