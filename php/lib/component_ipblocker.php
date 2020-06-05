<?php
namespace TheFramework\Components;

use TheFramework\Helpers\HelperRequest;
use TheFramework\Providers\ProviderBase;

class ComponentIpblocker
{

    private $req = null;
    private $prov;

    public function __construct()
    {
        $this->req = new HelperRequest();
        $this->prov = new ProviderBase($this->req->get_remoteip());
    }

    private function is_ipblacklisted()
    {
        $isblocked = $this->prov->is_blacklisted();
        return $isblocked;
    }

    private function check_forbidden_content()
    {
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
        We have detected malicious requests from your ip: {$ip}
        This address will be blacklisted for some time.
        If you consider this is not your case please contact eacevedof@hotmail.com
        </pre>
        <p>
        Powered by: <b>prj_ipblocker</b>
        </p>
        ";
    }

    public function handle_request()
    {
        $this->prov->save_request();
        //guarda en blacklist si detecta contenido prohibido
        $this->check_forbidden_content();
        if($this->is_ipblacklisted()){
            $this->response();
            $this->pr();
            die();
        }
    }
}