<?php
//ipblocker.php
require __DIR__."/../vendor/autoload.php";
use function Ipblocker\Functions\pp;
use Ipblocker\Controllers\ControllerMain;

//pp(get_included_files());
if(defined("IPB_DOTRACK") && IPB_DOTRACK) {
    (new ControllerMain)->handle_request();
}

//$pathboot = realpath(__DIR__ . "/../boot");
//include("$pathboot/appbootstrap.php");

//use \Ipblocker\Component\ComponentIpblocker;
//(new ComponentIpblocker())->handle_request();
