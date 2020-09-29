<?php
//ipblocker.php
require __DIR__."/../vendor/autoload.php";

//pp(get_included_files());
use Ipblocker\Controller\ControllerMain;
(new ControllerMain)->handle_request();


//$pathboot = realpath(__DIR__ . "/../boot");
//include("$pathboot/appbootstrap.php");

//use \Ipblocker\Component\ComponentIpblocker;
//(new ComponentIpblocker())->handle_request();
