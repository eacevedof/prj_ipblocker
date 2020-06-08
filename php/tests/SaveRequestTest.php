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

    private function _test_blocked_by_country()
    {
        //country n.a
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.5")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/contact/")
            ->add_post("hidAction","insert");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_by_country");
    }

    private function _test_blocked_OR_get()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.6")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/post-null/")
            ->add_get("content","die(@md5(")
            ->add_get("s","ftp://");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_OR_get");
    }

    private function _test_blocked_OR_post_dropbox()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.7")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/blocked-or-post/")
            ->add_post("textarea","dropbox.com/s/");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_OR_post_dropbox");
    }

    private function _test_blocked_OR_post_html()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.8")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/blocked-or-post/")
            ->add_post("textarea","<a href=\"http://somedomain.com\">pincha aqui</a>");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_OR_post_html");
    }

    private function _test_blocked_AND_pussy_pics()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.9")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/blocked-or-post/")
            ->add_post("textarea","
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquam aut pussy ex cupiditate distinctio, cumque 
            ea iste doloremque earum totam velit omnis debitis quas est pics non necessitatibus. Eaque, ut!
            ");

        $this->log_globals();
        $this->_execute_ipblocker("_test_blocked_AND_pussy_pics");
    }

    private function _test_NO_BLOCKED_AND_pusy_pixs()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.10")
            ->add_server("HTTP_HOST","theframework.es")
            ->add_server("REQUEST_URI","/en/blocked-or-post/")
            ->add_post("textarea","
            Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aliquam aut pusy ex cupiditate distinctio, cumque 
            ea iste doloremque earum totam velit omnis debitis quas est pix non necessitatibus. Eaque, ut!
            ");

        $this->log_globals();
        $this->_execute_ipblocker("_test_NO_BLOCKED_AND_pusy_pixs");
    }

    private function _test_NO_BLOCKED_post_req_without_post()
    {
        $this->reset_all()
            ->add_server("REMOTE_ADDR","192.168.1.12")
            ->add_server("HTTP_HOST","gracestyle.es")
            ->add_server("REQUEST_URI","/es/contacto/");
        $this->log_globals();
        $this->_execute_ipblocker("_test_NO_BLOCKED_post_req_without_post");
    }

    public function run()
    {
        $this->_test_NO_BLOCKED_post_req_without_post();
        /*
        $this->_test_non_blocked_post();
        $this->_test_blocked_by_post_required();

        $this->_test_blocked_by_get_not_null();
        $this->_test_blocked_by_post_not_null();
        $this->_test_blocked_by_country();
        $this->_test_blocked_OR_get();
        $this->_test_blocked_OR_post_dropbox();
        $this->_test_blocked_OR_post_html();
        $this->_test_blocked_AND_pussy_pics();
        $this->_test_NO_BLOCKED_AND_pusy_pixs();
        */
    }

}

//otra forma es lanzar: php -S localhost:2000 -t public
if (!count(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)))
    (new SaveRequestTest())->run();
