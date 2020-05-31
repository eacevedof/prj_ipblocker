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
        $this->prov = new ProviderBase();

    }

    private function is_blocked()
    {

    }

    public function handle_request()
    {

    }
}