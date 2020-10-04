<?php
namespace Ipblocker\Controllers;
use Ipblocker\Services\RequestService;

class ControllerMain
{
    public function __invoke()
    {
        (new RequestService())->handle_request();
    }
}