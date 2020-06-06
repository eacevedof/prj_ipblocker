<?php
namespace TheFramework\Components;

use TheFramework\Components\ComponentSearchbots as sb;
use TheFramework\Helpers\HelperRequest;
use TheFramework\Providers\ProviderBase;
use Theframework\Traits\TraitLog;

class ComponentIpblocker
{
    use TraitLog;

    private $req = null;
    private $prov;

    public function __construct()
    {
        $this->req = new HelperRequest();
        $this->prov = new ProviderBase($this->req->get_remoteip());
    }

    private function is_search_bot()
    {
        return sb::get_searchbot($this->req->get_remoteip());
    }

    private function is_ipblacklisted()
    {
        $isblocked = $this->prov->is_blacklisted();
        return $isblocked;
    }

    private function check_forbidden_content()
    {
        if($this->is_ipblacklisted())
            return;
        $words = $this->prov->get_forbidden_words();
        if($words)
            $this->prov->add_to_blacklist($words);
    }


    private function response()
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
        if($this->is_search_bot()) return;
        //guarda en blacklist si detecta contenido prohibido y si no existiera en bl
        $this->check_forbidden_content();

        if($this->is_ipblacklisted()){
            $this->response();
            $this->pr();
            die();
        }
    }

    public function test_handle_request($m="")
    {
        $this->prov->save_request();
        if($this->is_search_bot()) return;
        $this->check_forbidden_content();

        if($this->is_ipblacklisted())
            //echo "\nthis ip is blacklisted";
            throw new \Exception("\nthis ip is blacklisted $m");
        echo "\ntest passed for $m";
    }
}