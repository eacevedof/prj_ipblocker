<?php
// vendor/bin/phpunit tests/Unit/Components
namespace App\Tests\Unit\Components;

use function Ipblocker\Functions\cp;

use App\Tests\Unit\BaseTest;
use Ipblocker\Components\SearchbotsComponent as sb;

class SearchbotsComponentTest extends BaseTest
{

    private const IP_COUNTRY_GREECE = "154.57.3.132";//no detecta el país
    private const IP_COUNTRY_BRASIL = "177.8.225.26";

    public function _test_has_country_na()
    {
        $arwhois = sb::get_whois(self::IP_COUNTRY_GREECE);
//cp($arwhois,"arwhois",0);
        $country = "n.a";
        if($arwhois["country"]) $country = $arwhois["country"];
        $this->assertNotEmpty($country);
        $this->assertEquals("n.a",$country);
    }

    public function test_has_country_br()
    {
        $arwhois = sb::get_whois(self::IP_COUNTRY_BRASIL);
//cp($arwhois,"arwhois",0);
        $country = "n.a";
        if($arwhois["country"]) $country = $arwhois["country"];
        $this->assertNotEmpty($country);
        $this->assertEquals("BR",$country);
    }
}