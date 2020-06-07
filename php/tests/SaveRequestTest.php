<?php
include("BaseTest.php");
use Tests\BaseTest;

final class SaveRequestTest extends BaseTest
{
    //caso ideal
    private function _test_non_blocked_post()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.1")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/contact/")
            ->add_post("hidAction","insert");

        $this->log_globals();
        $this->_execute_ipblocker("_test_non_blocked_post");
    }

    private function _test_blocked_by_post_required()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.2")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/contact/")
            ->add_post("user","juan@mail.com")
            ->add_post("password","furnitopia.com");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_by_post_required");
    }

    private function _test_blocked_by_get_not_null()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.3")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/contact/")
            ->add_post("hidAction","insert")
            ->add_get("some","querystring")
            ->add_get("other","querystring");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_by_get_not_null");
    }

    private function _test_blocked_by_post_not_null()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.4")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/post-null/")
            ->add_post("hidAction","insert")
            ->add_get("s","plato de comida x");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_by_post_not_null");
    }

    private function _test_unicode_blocked_post()
    {
        $content = "\u0430\u043b"; //ал
        //$content = utf8_encode($content);
        $this->logd($content,"utf8?");
        $this->reset_all()->add_post("textarea",$content);
        $this->log_globals();
        $this->_execute_ipblocker("_test_unicode_blocked_post");
    }

    private function _test_blocked_get()
    {
        $this->reset_all()
            ->add_get("","ftp://")
            ->add_get("content","die(@md5(");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_get");
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
        //$this->_test_non_blocked_post();
        //$this->_test_blocked_by_post_required();
        //$this->_test_blocked_by_get_not_null();
        //$this->_test_blocked_by_post_not_null();

        //$this->_test_unicode_blocked_post();
        //$this->_test_blocked_get();
        //$this->_test_non_blocked_post();
        //$this->_test_blocked_by_post_html();
        //$this->_test_blocked_by_post_dropbox();
    }

}

//otra forma es lanzar: php -S localhost:2000 -t public
if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
    (new SaveRequestTest())->run();
