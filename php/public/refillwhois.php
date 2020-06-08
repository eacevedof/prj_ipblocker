<?php
//refillwhois.php
$pathboot = realpath(__DIR__."/../boot");
include("$pathboot/appbootstrap.php");

use \TheFramework\Components\ComponentIpblocker;
(new ComponentIpblocker())->refill_whois();
