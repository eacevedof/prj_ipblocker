<?php
namespace App\Tests\Unit\Providers;

use App\Tests\Unit\BaseTest;
use Ipblocker\Providers\BaseProvider;

class BaseProviderTest extends BaseTest
{
    private const IP_UNATRACKED = "127.0.0.U";
    private const IP_BLACKLISTED = "127.0.0.B";
    private const IP_NOTBLACKLISTED = "127.0.0.NB";
    private const IP_NOTREGISTERED = "127.0.0.NR";

    private function _add_untracked()
    {
        $db = $this->_get_db();
        //$arsql[0] = sprintf("DELETE FROM app_ip_untracked WHERE remote_ip='%s'",self::IP_UNATRACKED);
        $arsql[0] = sprintf("SELECT id FROM app_ip_untracked WHERE remote_ip='%s'",self::IP_UNATRACKED);
        $id = $db->query($arsql[0],0,0);

        $arsql[1] = sprintf("INSERT INTO app_ip_untracked (remote_ip) VALUES('%s')",self::IP_UNATRACKED);
        if(!$id) $db->exec($arsql[1]);
        if($db->is_error())
            $this->logd($db->get_errors(),"_add_untracked.errors");
    }

    private function _add_blacklist()
    {
        $db = $this->_get_db();
        $arsql[0] = sprintf("SELECT id FROM app_ip_blacklist WHERE remote_ip='%s'",self::IP_BLACKLISTED);
        $id = $db->query($arsql[0],0,0);

        $arsql[] = sprintf("INSERT INTO app_ip_blacklist (remote_ip) VALUES('%s')",self::IP_BLACKLISTED);
        $sql = implode(";",$arsql);
        if(!$id) $db->exec($sql);
        if($db->is_error())
            $this->logd($db->get_errors(),"_add_untracked.errors");
    }

    public function test_isuntracked()
    {
        $this->_add_untracked();
        $this->_add_server("REMOTE_ADDR", self::IP_UNATRACKED);

        $prov = new BaseProvider();
        $r = $prov->is_untracked();
        $this->assertTrue(($r>0));
    }

    public function test_tracked()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR", "fake-ip");

        $prov = new BaseProvider();
        $r = $prov->is_untracked();
        //print_r("r:");print_r($r);die;
        $this->assertEmpty($r);
    }

    public function test_isblacklisted()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR", self::IP_BLACKLISTED);

        $this->_add_blacklist();
        $prov = new BaseProvider();
        $r = $prov->is_blacklisted();
        $this->assertTrue($r>0);
    }

    public function test_isnot_blacklisted()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR", self::IP_NOTBLACKLISTED);

        $prov = new BaseProvider();
        $r = $prov->is_blacklisted();
        $this->assertEmpty($r);
    }

    public function test_isnot_registered()
    {
        $this->_reset_fullrequest();
        $this->_add_server("REMOTE_ADDR", self::IP_NOTREGISTERED);

        $prov = new BaseProvider();
        $r = $prov->is_registered();
        $this->assertEmpty($r);
    }


}