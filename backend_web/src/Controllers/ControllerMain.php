<?php
namespace Ipblocker\Controllers;
use Ipblocker\Services\RequestCheckService;

final class ControllerMain
{
    public function __invoke()
    {
        (new RequestCheckService())->handle_request();
    }
}