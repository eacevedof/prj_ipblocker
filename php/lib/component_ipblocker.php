<?php
namespace TheFramework\Components;

use TheFramework\Components\ComponentSearchbots as sb;
use TheFramework\Helpers\HelperRequest as req;
use TheFramework\Providers\ProviderBase;
use Theframework\Providers\ProviderKeywords;
use Theframework\Traits\TraitLog;

class ComponentIpblocker
{
    use TraitLog;

    private $req = null;
    private $prov;

    public function __construct()
    {
        $this->req = req::getInstance();
        $this->prov = new ProviderBase($this->req->get_remoteip());
    }

    private function _is_search_bot()
    {
        return sb::get_name($this->req->get_remoteip());
    }

    private function _is_ipblacklisted()
    {
        $isblocked = $this->prov->is_blacklisted();
        return $isblocked;
    }

    private function _check_forbidden_content()
    {
        if($this->_is_ipblacklisted())
            return;
        $words = (new ProviderKeywords())->is_forbidden();
        if($words)
            $this->prov->add_to_blacklist($words);
    }

    private function _response_headers()
    {
        $codes = send_httpstatus(403);
    }

    private function pr()
    {
        $ip = $this->req->get_remoteip();
        $now = date("Ymd His");
        echo "
        <pre>
        {$now}:
        We have detected some malicious requests from your ip: {$ip}
        This address will be blacklisted for some time around 24h.
        If you consider this is not your case please contact
            eacevedof@hotmail.com
        so we can enable your ip again sooner.
        </pre>
        <p>
        Powered by: <b>prj_ipblocker</b>
        </p>
        ";
    }

    public function handle_request()
    {
        $this->prov->save_request();
        if($this->_is_search_bot()) return;
        //guarda en blacklist si detecta contenido prohibido y si no existiera en bl
        $this->_check_forbidden_content();

        if($this->_is_ipblacklisted()){
            $this->_response_headers();
            $this->pr();
            die();
        }
    }

    public function refill_whois()
    {
        $this->prov->refill_whois();
    }

    public function test_handle_request($m="")
    {
        $this->prov->save_request();
        if($this->_is_search_bot()) return;
        $this->_check_forbidden_content();

        if($this->_is_ipblacklisted())
            //echo "\nthis ip is blacklisted";
            return print("\n result: blocked //");

        echo "\n result: no-blocked //";
    }
}