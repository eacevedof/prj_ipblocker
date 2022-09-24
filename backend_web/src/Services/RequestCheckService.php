<?php
namespace Ipblocker\Services;

//use function Ipblocker\Functions\pp;
use function Ipblocker\Functions\send_httpstatus;

use Ipblocker\Helpers\RequestHelper as req;
use Ipblocker\Providers\DbProvider;
use Ipblocker\Providers\IpdataProvider;
use Ipblocker\Traits\LogTrait as Log;

final class RequestCheckService
{
    use Log;

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

    private function _die_message()
    {
        $ip = $this->ipprovider->get_ip();
        $now = \date("Y-m-d H:i:s");

        $message = "
        <pre>
        {$now}:
        Sorry but this content is not longer available.
        
        Request from: <b>{$ip}</b>
        Powered by: <b>IP Blocker 2.0</b>
        </pre>
        ";
        send_httpstatus(404);
        die($message);
    }

    public function handle_request()
    {
        if($this->_is_ipuntracked()) return true;
        $this->dbprovider->save_request();

        if($this->_is_search_bot()) return true;
        //guarda en blacklist si detecta contenido prohibido
        $this->_check_forbidden_content();

        if($this->_is_ipblacklisted()){
            $this->_die_message();
        }
        return true;
    }

}