<?php
namespace Ipblocker\Providers;

use Ipblocker\Components\SearchbotsComponent as sb;
use Ipblocker\Components\ConfigComponent as cfg;
use Ipblocker\Components\Db\MysqlComponent;
use Ipblocker\Traits\LogTrait as Log;
use Ipblocker\Helpers\RequestHelper as req;

class BaseProvider
{
    use Log;

    private $req;
    private $db;
    private $remoteip;

    public function __construct()
    {
        $dbname = $this->_get_dbname_by_env();
        $config = cfg::get_schema("c1", $dbname);
        $this->db = new MysqlComponent($config);
        $this->req = req::getInstance();
        $this->remoteip = $this->req->get_remoteip();
    }

    private function _get_searchbot()
    {
        return sb::get_name($this->remoteip);
    }

    private function _get_dbname_by_env()
    {
        $env = cfg::get_env();
        if($env=="prod")
            return "dbs433062";
        return "db_security";
    }

    private function _save_app_ip()
    {
        $whois = $this->req->get_whois();
        $sql = "
        -- save_app_ip 1
        INSERT INTO app_ip (remote_ip, country, whois) VALUES('$this->remoteip','{$whois["country"]}','{$whois["whois"]}')";

        $searchbot = $this->_get_searchbot();
        if($searchbot)
            $sql = "
            -- save_app_ip 2
            INSERT INTO app_ip (remote_ip, country, whois) VALUES('$this->remoteip','{$whois["country"]}','host:$searchbot')";

        $this->db->exec($sql);
    }

    private function _to_json($arvar)
    {
        if($arvar) {
            $string = json_encode($arvar);
            $string = str_replace("'","\\'",$string);
            return $string;
        }
        return "";
    }

    public function is_blacklisted()
    {
        $sql = "
        -- is_blacklisted
        SELECT id FROM app_ip_blacklist WHERE remote_ip='$this->remoteip' AND is_blocked=1";
        $id = $this->db->query($sql,0,0);
        //print_r($id);
        return $id;
    }

    public function is_untracked()
    {
        $sql = "
        -- is_untracked
        SELECT id FROM app_ip_untracked WHERE remote_ip='$this->remoteip' AND is_enabled=1";
        $id = $this->db->query($sql,0,0);
        //print_r($id);
        return $id;
    }

    public function is_registered()
    {
        $sql = "
        -- is_registered
        SELECT id FROM app_ip WHERE remote_ip='$this->remoteip'";
        $id = $this->db->query($sql,0,0);
        return $id;
    }

    public function save_request()
    {
        if(!$this->is_registered()) $this->_save_app_ip();

        $requesturi = addslashes($this->req->get_requri());
        $domain = $this->req->get_domain();
        $get = $this->_to_json($_GET);
        $post = $_POST;
        if(isset($post["password"])) $post["password"] = "****";
        $post = $this->_to_json($post);
        $files = $this->_to_json($_FILES);

        $sql = "
        -- save_request
        INSERT INTO app_ip_request (`remote_ip`,`domain`,`request_uri`,`post`,`get`,`files`) 
        VALUES ('$this->remoteip','$domain','$requesturi','$post','$get','$files')";
        $this->db->exec($sql);
    }

    public function add_to_blacklist($kws)
    {
        $sql = "
        -- add_to_blacklist
        INSERT INTO app_ip_blacklist (`remote_ip`,`reason`) 
        VALUES ('$this->remoteip','rules: $kws')";
        $this->db->exec($sql);
    }

    public function refill_whois()
    {
        $sql = "SELECT remote_ip FROM app_ip WHERE 1 AND whois IS NULL ORDER BY id DESC LIMIT 31";
        $ips = $this->db->query($sql);
        $ips = array_column($ips,"remote_ip");
        $arupdates = [];
        foreach ($ips as $ip)
        {
            $host = sb::get_host($ip);
            $arwhois = sb::get_whois($ip);

            $country = $arwhois["country"];
            $whois = "host:$host, org:{$arwhois["whois"]}";

            $sql = "UPDATE app_ip SET country='$country', whois='$whois' WHERE 1 AND remote_ip='$ip'";
            $arupdates[] = $sql;
        }

        $this->logd($arupdates,"arupdates");
        if($arupdates){
            $sql = implode(";",$arupdates);
            $this->db->exec($sql);
        }
    }
}