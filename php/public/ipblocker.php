<?php
//ipblocker.php
include("../lib/functions.php");
include("../lib/component_ipblocker.php");


$o = new \TheFramework\Components\ComponentIpblocker();
$o->handle_request();

echo "ipblocker.php";