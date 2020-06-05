<?php
//ipblocker.php
$pathboot = realpath(__DIR__."/../boot");
include("$pathboot/appbootstrap.php");

$o = new \TheFramework\Components\ComponentIpblocker();
$o->handle_request();
