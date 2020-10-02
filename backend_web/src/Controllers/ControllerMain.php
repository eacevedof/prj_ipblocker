<?php
namespace Ipblocker\Controllers;

use function Ipblocker\Functions\send_httpstatus;
use Ipblocker\Components\SearchbotsComponent as sb;
use Ipblocker\Helpers\RequestHelper as req;
use Ipblocker\Providers\DbProvider;
use Ipblocker\Services\RulezChecker;
use Ipblocker\Traits\LogTrait;

class ControllerMain
{
    use LogTrait;

    private $req;
    private $provider;

    public function __construct()
    {
        $this->req = req::getInstance();
        $this->dbprovider = new DbProvider();
    }

    private function _is_search_bot()
    {
        return sb::get_name($this->req->get_remoteip());
    }

    private function _is_ipuntracked()
    {
        $isuntracked = $this->dbprovider->is_untracked();
        return $isuntracked;
    }

    private function _is_ipblacklisted()
    {
        $isblocked = $this->dbprovider->is_blacklisted();
        return $isblocked;
    }

    private function _check_forbidden_content()
    {
        if($this->_is_ipblacklisted())
            return;
        $words = (new RulezChecker())->is_forbidden();
        if($words)
            $this->dbprovider->add_to_blacklist($words);
    }

    private function _response_headers()
    {
        //send_hhtpstatus configura headers: header($http[$num]);
        $codes = send_httpstatus(403);
    }

    private function _pr()
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
        if($this->_is_ipuntracked()) return;
        $this->dbprovider->save_request();
        if($this->_is_search_bot()) return;
        //guarda en blacklist si detecta contenido prohibido y si no existiera en bl
        $this->_check_forbidden_content();

        if($this->_is_ipblacklisted()){
            $this->_response_headers();
            $this->_pr();
            die();
        }
        return true;
    }

    public function refill_whois()
    {
        $this->dbprovider->_refill_whois_na();
    }

    public function test_handle_request()
    {
        //si no se debe guardar ningun tipo de registro de esta ip
        if($this->_is_ipuntracked()) return;

        $this->dbprovider->save_request();
        if($this->_is_search_bot()) return;
        $this->_check_forbidden_content();

        if($this->_is_ipblacklisted())
            //echo "\nthis ip is blacklisted";
            return print("\n result: blocked //");

        echo "\n result: no-blocked //";
    }
}