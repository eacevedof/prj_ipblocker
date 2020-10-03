<?php
namespace Ipblocker\Providers;

use Ipblocker\Components\ConfigComponent as cfg;
use Ipblocker\Components\Db\MysqlComponent;
use Ipblocker\Traits\LogTrait as Log;
use Ipblocker\Helpers\RequestHelper as req;

class DbProvider
{
    use Log;

    private $req;
    private $db;
    private $ipprovider;

    public function __construct()
    {
        $dbname = $this->_get_dbname_by_env();
        $config = cfg::get_schema("c1", $dbname);
        $this->db = new MysqlComponent($config);
        $this->req = req::getInstance();
        $this->ipprovider = new IpdataProvider($this->req->get_remoteip());
    }

    private function _get_dbname_by_env()
    {
        $env = cfg::get_env();
        if($env=="local") return "db_ipblocker";
        if($env=="prod") return "dbs433062";
        if($env=="test") return "dbs863900";
    }

    private function _save_app_ip()
    {
        $sql = "
        -- save_app_ip 1
        INSERT INTO app_ip (remote_ip, country, whois) VALUES('%s','%s','%s')";
        $sql = sprintf($sql,
                $this->ipprovider->get_ip(),$this->ipprovider->get_country(),$this->ipprovider->get_whois());

        $searchbot = $this->ipprovider->get_searchbot();
        if($searchbot) {
            $sql = "
            -- save_app_ip 2
            INSERT INTO app_ip (remote_ip, country, whois) VALUES('%s','%s','%s')";
            $sql = sprintf($sql,
                    $this->ipprovider->get_ip(),$this->ipprovider->get_country(),$searchbot);
        }

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
        SELECT id FROM app_ip_blacklist WHERE remote_ip='{$this->ipprovider->get_ip()}' AND is_blocked=1";
        $id = $this->db->query($sql,0,0);
        //print_r($id);
        return $id;
    }

    public function is_untracked()
    {
        $sql = "
        -- is_untracked
        SELECT id FROM app_ip_untracked WHERE remote_ip='{$this->ipprovider->get_ip()}' AND is_enabled=1";
        $id = $this->db->query($sql,0,0);
        //print_r($id);
        return $id;
    }

    public function is_registered()
    {
        $sql = "
        -- is_registered
        SELECT id FROM app_ip WHERE remote_ip='{$this->ipprovider->get_ip()}'";
        $id = $this->db->query($sql,0,0);
        return $id;
    }

    public function save_request()
    {
        if(!$this->is_registered()) $this->_save_app_ip();

        $requesturi = addslashes($this->req->get_requri());
        $domain = $this->req->get_domain();
        $get = $this->req->get_get();
        $post = $this->req->get_post();
        $files = $this->req->get_files();
        $useragent = $this->req->get_useragent();
        if(isset($post["password"])) $post["password"] = "****";

        $get = $this->_to_json($get);
        $post = $this->_to_json($post);
        $files = $this->_to_json($files);

        $sql = "
        -- save_request
        INSERT INTO app_ip_request (`remote_ip`,`domain`,`request_uri`,`post`,`get`,`files`,`user_agent`) 
        VALUES ('{$this->ipprovider->get_ip()}','$domain','$requesturi','$post','$get','$files','$useragent')";
        $this->db->exec($sql);
    }

    public function add_to_blacklist($kws)
    {
        $sql = "
        -- add_to_blacklist
        INSERT INTO app_ip_blacklist (`remote_ip`,`reason`) 
        VALUES ('{$this->ipprovider->get_ip()}','rules: $kws')";
        $this->db->exec($sql);
    }

    public function refill_whois_na()
    {
        $sql = "SELECT remote_ip FROM app_ip WHERE 1 AND whois='n.a|n.a' ORDER BY id DESC LIMIT 31";
        $ips = $this->db->query($sql);
        $ips = array_column($ips,"remote_ip");
        $arupdates = [];
        foreach ($ips as $ip)
        {
            $ipprov = new IpdataProvider($ip);
            $host = $ipprov->get_host();
            $country = $ipprov->get_country();
            $whois = "host:$host, org:{$ipprov->get_whois()}";

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