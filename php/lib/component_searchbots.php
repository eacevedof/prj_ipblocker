<?php
namespace TheFramework\Components;

class ComponentSearchbots
{
    private static $botsns = [
        "baidu1"=>".crawl.baidu.com",
        "baidu2"=>".crawl.baidu.jp",
        "bing1"=>".search.msn.com",
        "google1"=>".google.com",
        "google2"=>".googlebot.com",
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

    public static function get_name($remoteip)
    {
        $output = [];
        exec("host $remoteip",$output);
        $output = $output[0];
        foreach (self::$botsns as $botname => $ns)
            if(strstr($output,$ns))
                return $botname;

        foreach (self::$botsip as $botname => $arips)
            if(in_array($remoteip,$arips))
                return $botname;

        return "";
    }
}