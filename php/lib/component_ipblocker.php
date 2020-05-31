<?php
namespace TheFramework\Components;
class ComponentIpblocker
{
    private $post;
    private $get;
    private $requrl;
    private $remoteip;

    public function __construct()
    {
        $this->post = $_POST;
        $this->get = $_GET;
        $this->requrl = $_SERVER["REQUEST_URI"];
        $this->remoteip = $_SERVER["REMOTE_ADDR"];
    }

    private function is_blocked()
    {

    }

    public function handle_request()
    {

    }
}