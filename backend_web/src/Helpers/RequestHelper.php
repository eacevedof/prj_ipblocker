<?php
namespace Ipblocker\Helpers;

use function Ipblocker\Functions\cp;

final class RequestHelper
{
    private $remoteip;
    private $domain;
    private $requri;
    private $useragent;

    private $get;
    private $post;
    private $files;

    private static $thisself = null;

    /**
     * is not allowed to call from outside to prevent from creating multiple instances,
     * to use the RequestHelper, you have to obtain the instance from RequestHelper::getInstance() instead
     */
    private function __construct()
    {
//cp($_SERVER,"reqhelper.construct.server",0);
        $this->remoteip = $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1";
        $this->domain = $_SERVER["HTTP_HOST"] ?? "*";
        $this->requri = $_SERVER["REQUEST_URI"] ?? "";
        $this->useragent = $_SERVER["HTTP_USER_AGENT"] ?? "";

        $this->get = $_GET ?? [];
        $this->post = $_POST ?? [];
        $this->files = $_FILES ?? [];
        $this->_load_whois();
    }

    /**
     * @return RequestHelper
     */
    public static function getInstance(): RequestHelper
    {
        if (static::$thisself === null) static::$thisself = new static();
        return static::$thisself;
    }

    /**
     * @return mixed|string
     */
    public function get_requri($qs=0){
//cp($this->requri,"get_requri. this->requri querystring:$qs",0);
        if(!$qs) return $this->requri;
        $parts = explode("?",$this->requri);
        return $parts[0];
    }

    /**
     * @return mixed|string
     */
    public function get_domain(){ return $this->domain;}

    /**
     * @return mixed|string
     */
    public function get_remoteip(){ return $this->remoteip;}

    /**
     * @return array
     */
    public function get_get(): array {return $this->get;}

    /**
     * @return array
     */
    public function get_post(): array {return $this->post;}

    /**
     * @return array
     */
    public function get_files(): array {return $this->files;}

    public function is_key($k,$in="post"): bool
    {
        if($in=="post")  return in_array($k,array_keys($this->post));
        if($in=="get") return in_array($k,array_keys($this->get));
        if($in=="files") return in_array($k,array_keys($this->files));
        return false;
    }

    public function get_key($k, $in="post")
    {
        if($in=="post") return $this->post[$k] ?? null;
        if($in=="get") return $this->get[$k] ?? null;
        if($in=="files") return $this->files[$k] ?? null;
    }

    public function get_useragent(){return $this->useragent;}

     public static function reset()
    {
        self::$thisself = null;
    }

    /**
     * prevent the instance from being cloned (which would create a second instance of it)
     */
    private function __clone(){}

    /**
     * prevent from being unserialized (which would create a second instance of it)
     */
    private function __wakeup(){}    
}