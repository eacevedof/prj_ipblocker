<?php
namespace Ipblocker\Providers;

use function Ipblocker\Functions\endswith;
use Ipblocker\Providers\CmdComponent as cmd;
use Ipblocker\Components\SearchbotsComponent as bot;

class IpdataProvider
{
    public const NOT_APPLICABLE = "n.a";

    private $remoteip;
    private $arwhois;
    private $arhost;

    public function __construct(string $remoteip)
    {
        $this->remoteip = $remoteip;
        $this->_load_whois();
        $this->_load_host();
    }

    private function _load_whois()
    {
        $this->arwhois = cmd::get_whois($this->remoteip);
        if(!isset($this->arwhois["country"]))
            $this->arwhois["country"] = self::NOT_APPLICABLE;
    }

    private function _get_host_country(string $hostraw)
    {
        $pieces = explode(" ",$hostraw);
        $lastone = end($pieces);

        //lastone: projelmec.static.gvt.net.br.
        $pieces = explode(".",$lastone);
        $pieces = array_pop($pieces);
        $lastone = end($pieces);
        return strtoupper($lastone);
    }

    private function _load_host()
    {
        $hostraw = cmd::get_host($this->remoteip);
        $hostraw = trim(strtolower($hostraw));
        $this->arhost["raw"] = $hostraw;
        $this->arhost["pieces"] = explode(" ",$hostraw);
        $this->arhost["country"] = $this->_get_host_country($hostraw);
    }

    private function _get_whois()
    {
        $config = [
            "netname" => [
                "netname","nserver",
            ],
            "organisation" => [
                "organisation","owner","ownerid"
            ]
        ];

        $netname = self::NOT_APPLICABLE;
        $organisation = self::NOT_APPLICABLE;

        foreach ($config["netname"] as $alias)
            if(isset($this->arwhois[$alias])){
                $netname = trim($this->arwhois[$alias]);
                break;
            }

        foreach ($config["organisation"] as $alias)
            if(isset($this->arwhois[$alias])){
                $organisation = trim($this->arwhois[$alias]);
                break;
            }

        return "$netname | $organisation";
    }

    private function _is_no_country(){return $this->arwhois["country"] === self::NOT_APPLICABLE;}

    public function get_whois() : string
    {
        return $this->_get_whois();
    }

    public function get_country() : string
    {
        if($this->_is_no_country())
            return $this->arhost["country"];

        return $this->arwhois["country"];
    }

    public function get_searchbot() : string
    {
        return bot::get_name($this->remoteip);
    }

}