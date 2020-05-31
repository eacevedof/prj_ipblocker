<?php
namespace TheFramework\Helpers;

class HelperRequest
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

    public function get_get($key)
    {
        return $this->get[$key] ?? "";
    }

    public function get_post($key)
    {
        return $this->post[$key] ?? "";
    }

    public function get_requrl() { return $this->requrl; }

    public function get_remoteip() { return $this->remoteip; }
}