<?php
// ./vendor/bin/phpunit ./tests/ExampleTest.php --color=auto
use PHPUnit\Framework\TestCase;
use Ipblocker\Traits\LogTrait as Log;

class ConfigTest extends TestCase
{
    use Log;

    public function test_exists_contexts_json()
    {
        $sFile = IPB_PATH_CONFIG."/contexts.json";
        //$this->log($sFile);
        $isFile = is_file($sFile);
        $this->assertEquals(TRUE,$isFile);
    }

    /**
     *  @depends test_exists_contexts_json
     */
    public function test_exists_rules_json()
    {
        $sFile = IPB_PATH_CONFIG."/rulez.json";
        //$this->log($sFile);
        $isFile = is_file($sFile);
        $this->assertEquals(TRUE,$isFile);
    }

}//ConfigTest