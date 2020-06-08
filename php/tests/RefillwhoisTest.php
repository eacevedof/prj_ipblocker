<?php
include("BaseTest.php");
use Tests\BaseTest;
use TheFramework\Components\ComponentIpblocker;

final class RefillwhoisTest extends BaseTest
{

    protected function _execute_refill($m)
    {
        echo "\n==================\n";
        echo "$m";
        echo "\n==================\n";
        (new ComponentIpblocker())->refill_whois();
    }

    private function _test_refill_whois()
    {
        $this->_execute_refill("_test_refill_whois");
    }

    public function run()
    {
        $this->_test_refill_whois();
    }
}

//otra forma es lanzar: php -S localhost:2000 -t public
if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
    (new RefillwhoisTest())->run();
