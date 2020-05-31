<?php
namespace TheFramework\Helpers;

class HelperRequest
{
    private $remoteip;

    public function __construct()
    {
        $this->remoteip = $_SERVER["REMOTE_ADDR"];
    }

    public function get_remoteip() { return $this->remoteip; }
}