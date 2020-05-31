<?php
namespace TheFramework\Components;
include("helper_request.php");
include("component_mysql.php");
include("component_mailing.php");
include("provider_base.php");

use TheFramework\Helpers\HelperRequest;

class ComponentIpblocker
{

    private $req = null;

    public function __construct()
    {
        $this->req = new HelperRequest();
    }

    private function is_blocked()
    {

    }

    public function handle_request()
    {

    }
}