<?php
namespace Ipblocker\Components;

use function Ipblocker\Functions\cp;

class SearchbotsComponent
{
    private static $botsns = [
        "baidu1"=>".crawl.baidu.com",
        "baidu2"=>".crawl.baidu.jp",
        "msn"=>".msn.com",
        "google1"=>".google.com",
        "google2"=>".googlebot.com",
        "google3-user"=>".googleusercontent.com",
        "yahoo1"=>".crawl.yahoo.net",
        "yandex1"=>".yandex.ru",
        "yandex2"=>".yandex.net",
        "yandex3"=>".yandex.com",
    ];

    private static $botsip = [
        //https://help.duckduckgo.com/duckduckgo-help-pages/results/duckduckbot/
         "duckduckgo" => [
            "23.21.227.69",
            "40.88.21.235",
            "50.16.241.113",
            "50.16.241.114",
            "50.16.241.117",
            "50.16.247.234",
            "52.204.97.54",
            "52.5.190.19",
            "54.197.234.188",
            "54.208.100.253",
            "54.208.102.37",
            "107.21.1.8",
         ]
    ];

    private const NOT_APPLICABLE = "n.a";

    public static function get_name($remoteip)
    {
        $output = [];
        exec("host $remoteip",$output);
        $output = trim($output[0]);
        foreach (self::$botsns as $botname => $ns)
            if(strstr($output,$ns))
                return $botname;

        foreach (self::$botsip as $botname => $arips)
            if(in_array($remoteip,$arips))
                return $botname;

        return "";
    }

    private static function _get_whoisarray($output)
    {
//cp($output,"whois output");
        $arwhois = [];
        foreach ($output as $i=> $strdata)
        {
            $parts = explode(": ",$strdata);
            if(count($parts) >1 )
                $arwhois[trim(strtolower($parts[0]))] = trim($parts[1]);
        }
        return $arwhois;
    }

    public static function get_host($remoteip)
    {
        $output = [];
        exec("host $remoteip",$output);
//cp($output,"get_host.output");
        $parts = explode(" ",$output[0] ?? "n.f");
        return trim(end($parts));
    }

    private static function _get_whois($arwhois)
    {
        $config = [
            "netname" => [
                "netname","nserver",
            ],
            "organisation" => [
                "organisation","owner"
            ]
        ];

        $netname = self::NOT_APPLICABLE;
        $organisation = self::NOT_APPLICABLE;

        foreach ($config["netname"] as $alias)
            if(isset($arwhois[$alias])){
                $netname = trim($arwhois[$alias]);
                break;
            }

        foreach ($config["organisation"] as $alias)
            if(isset($arwhois[$alias])){
                $organisation = trim($arwhois[$alias]);
                break;
            }

        return "$netname | $organisation";
    }

    private static function _ends_with($string, $sufix)
    {
        $length = strlen($sufix);
        if(!$length) return true;

        return substr($string, -$length ) === $sufix;
    }

    private static function _get_country($arwhois)
    {
        $country = $arwhois["country"] ?? self::NOT_APPLICABLE;
        if($country==self::NOT_APPLICABLE){
            $name = $arwhois["nserver"] ?? "";
            if(!$name) $name = $arwhois["netname"] ?? "";
            if(self::_ends_with($name,".br")) $country == "BR";
        }
        return $country;
    }

    public static function get_whois($remoteip)
    {
        $output = [];
        exec("whois $remoteip",$output);
        $arwhois = self::_get_whoisarray($output);
//cp($arwhois,"get_whois.arwhois ($remoteip)",0);
        return [
            "country" => self::_get_country($arwhois),
            "whois" => self::_get_whois($arwhois),
        ];
    }

}