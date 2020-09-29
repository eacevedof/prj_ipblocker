<?php
// en: /<project>/php
// ./vendor/bin/phpunit ./tests/ExampleTest.php --color=auto
use PHPUnit\Framework\TestCase;
use Ipblocker\Traits\LogTrait as Log;

class ExampleTest extends TestCase
{
    use Log;

    public function test_exists_config_file()
    {
        $sFile = __DIR__."/../../config/contexts.json";
        //$this->log($sFile);
        $isFile = is_file($sFile);
        $this->assertEquals(TRUE,$isFile);
    }

    /**
     *  @depends test_exists_config_file
     */
    public function est_is_env_prod()
    {

    }

    /**
     *  @depends test_exists_config_file
     */
    public function est_connection()
    {

    }

}//ExampleTest