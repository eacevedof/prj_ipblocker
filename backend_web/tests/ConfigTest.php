<?php
namespace Tests;
//php ./vendor/bin/phpunit ./tests
use PHPUnit\Framework\TestCase;
use function Ipblocker\Functions\cp;
use Ipblocker\Traits\LogTrait as Log;
use Ipblocker\Components\ConfigComponent as cfg;

class ConfigTest extends TestCase
{
    use Log;

    private function _get_dbname_by_env()
    {
        $env = cfg::get_env();
        if($env=="local") return "db_ipblocker";
        if($env=="prod") return "dbs433062";
        if($env=="test") return "dbs863900";
    }

    private function _get_jsonfile_by_env($type="contexts")
    {
        $env = cfg::get_env();
        $dir = "./config";
        $pathdir = realpath($dir);
        //cp($pathdir,"pathdir");
        $pathfile = "$pathdir/$type";

        if($env!=="prod"){
            $pathfile = $pathfile.".$env.json";
        }
        else
            $pathfile = $pathfile.".json";
        return $pathfile;
    }

    public function test_not_emptyrules()
    {
        $rules = cfg::get_rulez();
        $this->assertNotEmpty($rules);
    }

    public function test_not_emptyenv()
    {
        $env = cfg::get_env();
        $this->assertNotEmpty($env);
    }

    public function test_is_logfolder()
    {
        $isdir = is_dir(IPB_PATH_LOGS);
        $this->assertEquals(TRUE,$isdir);
    }

    public function test_not_empty_dbconfig()
    {
        $dbname = $this->_get_dbname_by_env();
        $dbconf = cfg::get_schema("c1", $dbname);
        $this->logd($dbconf,"test_not_empty_dbconfig.dbconf");
        $this->assertNotEmpty($dbconf);
    }

    private function _is_good_json($pathjson)
    {
        $string = file_get_contents($pathjson);
        $r = json_decode($string);
        $isok = (json_last_error() == JSON_ERROR_NONE);
        //$this->assertEquals(TRUE, $isok);
        if(json_last_error())
            $this->logd(json_last_error(),"_is_good_json: $pathjson");
        return $isok;
    }

    public function test_good_jsons()
    {
        $isok = $this->_is_good_json($this->_get_jsonfile_by_env());
        $this->assertEquals(TRUE, $isok);

        $isok = $this->_is_good_json($this->_get_jsonfile_by_env("rulez"));
        $this->assertEquals(TRUE, $isok);
    }



}//ConfigTest