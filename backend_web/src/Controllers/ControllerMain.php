<?php
namespace Ipblocker\Controllers;

use function Ipblocker\Functions\send_httpstatus;
use Ipblocker\Helpers\RequestHelper as req;
use Ipblocker\Providers\DbProvider;
use Ipblocker\Providers\IpdataProvider;
use Ipblocker\Services\RulescheckerService;
use Ipblocker\Traits\LogTrait;

class ControllerMain
{
    use LogTrait;

    private $dbprovider;
    private $ipprovider;

    public function __construct()
    {
        $remoteip = req::getInstance()->get_remoteip();
        $this->dbprovider = new DbProvider();
        $this->ipprovider = new IpdataProvider($remoteip);
    }

    private function _is_search_bot(){return $this->ipprovider->get_searchbot();}

    private function _is_ipuntracked(){return $this->dbprovider->is_untracked();}

    private function _is_ipblacklisted(){return $this->dbprovider->is_blacklisted();}

    private function _check_forbidden_content()
    {
        if($this->_is_ipblacklisted())  return;
        $reason = (new RulescheckerService())->is_forbidden();
        if($reason)
            $this->dbprovider->add_to_blacklist($reason);
    }

    private function _exception()
    {
        $ip = $this->ipprovider->get_ip();
        $now = date("Ymd His");

        $message = "
        <pre>
        {$now}:
        
        We have detected some malicious requests from your ip: 
            <b>{$ip}</b>
        
        This address will be blacklisted for some time (around 24h).
        If you consider this is not your case please contact
            eacevedof@hotmail.com
        so we can enable your ip again sooner.
        
        Powered by: <b>IP Blocker 2.0</b>
        </pre>
        ";
        send_httpstatus(403);
        throw new \Exception($message);
    }

    public function handle_request()
    {
        if($this->_is_ipuntracked()) return true;
        $this->dbprovider->save_request();
        if($this->_is_search_bot()) return true;
        //guarda en blacklist si detecta contenido prohibido
        $this->_check_forbidden_content();

        if($this->_is_ipblacklisted()){
            $this->_exception();
        }
        return true;
    }
}