<?php
include("BaseTest.php");
use Tests\BaseTest;
use \TheFramework\Components\ComponentIpblocker;


final class SaveRequestTest extends BaseTest
{

    private function pr()
    {
        //print_r($_POST);
        //print_r($_GET);
        //print_r($_FILES);
    }

    private function _execute_ipblocker($m)
    {
        echo "\n==================\n";
        echo "$m";
        echo "\n==================\n";
        (new ComponentIpblocker())->test_handle_request($m);
    }

    private function _test_blocked_get()
    {
        $this->reset_all()
            ->add_get("","=ftp://")
            ->add_get("content","die(@md5(");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_get");
    }

    private function _test_non_blocked_get()
    {
        $this->reset_all()
            ->add_get("","ftp://")
            ->add_get("content","(@md5(");

        $this->log_globals();
        $this->_execute_ipblocker("_test_non_blocked_get");
    }

    private function _test_non_blocked_post()
    {
        $this->reset_all()
            ->add_post("user","juan@mail.com")
            ->add_post("password","furnitopia.com");

        $this->log_globals();
        $this->_execute_ipblocker("_test_non_blocked_post");
    }

    private function _test_blocked_by_post_dropbox()
    {
        $this->reset_all()
            ->add_post("textarea","dropbox.com/s/sometoken");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_by_post_dropbox");
    }

    private function _test_blocked_by_post_html()
    {
        $this->reset_all()
            ->add_post("textarea","<a href=\"http://somedomain.com\">pincha aqui</a>");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_by_post_html");
    }

    public function run()
    {
        $this->_test_non_blocked_post();
        $this->_test_non_blocked_get();
        //$this->_test_blocked_get();
        //$this->_test_non_blocked_post();
        //$this->_test_blocked_by_post_html();
        //$this->_test_blocked_by_post_dropbox();

    }
}

//otra forma es lanzar: php -S localhost:2000 -t public
if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
    (new SaveRequestTest())->run();
