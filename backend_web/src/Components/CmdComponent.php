<?php
namespace Ipblocker\Providers;

use Ipblocker\Traits\LogTrait as Log;
use function Ipblocker\Functions\endswith;


class CmdComponent
{
    use Log;

    private const CMD_WHOIS = "whois %";
    private const CMD_HOST = "host %";
    //public const NOT_APPLICABLE = "n.a";

    public static function exec($cmd)
    {
        $output = [];
        exec($cmd,$output);
        return $output;
    }

    private static function _get_whoisarray(array $output)
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

    public static function get_host($remoteip) :string
    {
        $cmd = sprintf(self::CMD_HOST, $remoteip);
        $output = self::exec($cmd);
        return $output[0] ?? "";
        //$parts = explode(" ",$output[0] ?? "n.f");
        //return trim(end($parts));
    }

    public static function get_whois($remoteip) :array
    {
        $cmd = sprintf(self::CMD_WHOIS, $remoteip);
        $output = self::exec($cmd);
        $arwhois = self::_get_whoisarray($output);
        return $arwhois;
    }
}