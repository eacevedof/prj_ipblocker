<?php
namespace Ipblocker\Components;

use function Ipblocker\Functions\cp;
use Ipblocker\Providers\CmdComponent as cmd;

class SearchbotsComponent
{

    private static $botsns = [
        "baidu1"        => ".crawl.baidu.com",
        "baidu2"        => ".crawl.baidu.jp",
        "msn"           => ".msn.com",
        "google1"       => ".google.com",
        "google2"       => ".googlebot.com",
        "google3-user"  => ".googleusercontent.com",
        "yahoo1"        => ".crawl.yahoo.net",
        "yandex1"       => ".yandex.ru",
        "yandex2"       => ".yandex.net",
        "yandex3"       => ".yandex.com",
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

    private const CMD_WHOIS = "whois %";

    public static function get_name($remoteip) : string
    {
        $cmd = sprintf(self::CMD_WHOIS, $remoteip);
        $output = cmd::exec($cmd);
        $output = trim($output[0]);

        //busco por nombre de servidor
        foreach (self::$botsns as $botname => $ns)
            if(strstr($output, $ns))
                return $botname;

        //busco por su ip
        foreach (self::$botsip as $botname => $arips)
            if(in_array($remoteip, $arips))
                return $botname;

        return "";
    }

}