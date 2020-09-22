<?php
include("BaseTest.php");
use Tests\BaseTest;
use Ipblocker\Component\ComponentIpblocker;
use Ipblocker\Component\ComponentSearchbots as sb;

final class RefillwhoisTest extends BaseTest
{

    protected function _execute_refill($m)
    {
        $now = date("Ymd His");
        echo "\n=======START: $now ===========\n";
        echo "$m";
        echo "\n==========================\n";
        (new ComponentIpblocker())->refill_whois();
        $now = date("Ymd His");
        echo "\n=========END: $now =============\n";
    }

    private function _test_host_google()
    {
        print_r("\n_test_host_google()");
        $ipsgoogle = [
            "66.249.73.112","66.249.79.102","35.228.238.99"
        ];

        foreach ($ipsgoogle as $ip) {
            $hostname = sb::get_host($ip);
            $botname = sb::get_name($ip);
            print_r("\nip:$ip, hostname:$hostname, botname: $botname");
        }
    }

    private function _test_host_yandex()
    {
        print_r("\n_test_host_yandex()");
        $ipsgoogle = ["77.88.9.132", "77.88.9.130"];
        foreach ($ipsgoogle as $ip) {
            $hostname = sb::get_host($ip);
            $botname = sb::get_name($ip);
            print_r("\nip:$ip, hostname:$hostname, botname: $botname");
        }
    }

    private function _test_refill_whois()
    {
        $this->_execute_refill("_test_refill_whois");
    }

    public function run()
    {
        $this->_test_refill_whois();
        //$this->_test_host_google();
        //$this->_test_host_yandex();
    }
}

//otra forma es lanzar: php -S localhost:2000 -t public
if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
    (new RefillwhoisTest())->run();
