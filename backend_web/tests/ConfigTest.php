<?php
namespace Tests;
// ./vendor/bin/phpunit ./tests/ExampleTest.php --color=auto
use PHPUnit\Framework\TestCase;
use Ipblocker\Traits\LogTrait as Log;

class ConfigTest extends TestCase
{
    use Log;

    private const FILE_JSON_CONTEXTS = IPB_PATH_CONFIG."/contexts.json";
    private const FILE_JSON_RULEZ = IPB_PATH_CONFIG."/rulez.json";

    public function test_exists_contexts_json()
    {
        $isFile = is_file(self::FILE_JSON_CONTEXTS);
        $this->assertEquals(TRUE,$isFile);
    }

    /**
     *  @depends test_exists_contexts_json
     */
    public function test_exists_rules_json()
    {
        $isFile = is_file(self::FILE_JSON_RULEZ);
        $this->assertEquals(TRUE,$isFile);
    }

    public function test_is_logfolder()
    {
        $isdir = is_dir(IPB_PATH_LOGS);
        $this->assertEquals(TRUE,$isdir);
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

    public function test_contexts_json()
    {
        $isok = $this->_is_good_json(self::FILE_JSON_CONTEXTS);
        $this->assertEquals(TRUE, $isok);
    }

    public function test_rules_json()
    {
        $isok = $this->_is_good_json(self::FILE_JSON_RULEZ);
        $this->assertEquals(TRUE, $isok);
    }

}//ConfigTest