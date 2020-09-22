<?php
//ipblocker.php
require __DIR__."/../vendor/autoload.php";

use Ipblocker\Controller\ControllerMain;
(new ControllerMain)->handle_request();

//print_r(get_included_files());
//$pathboot = realpath(__DIR__ . "/../boot");
//include("$pathboot/appbootstrap.php");

//use \Ipblocker\Component\ComponentIpblocker;
//(new ComponentIpblocker())->handle_request();
