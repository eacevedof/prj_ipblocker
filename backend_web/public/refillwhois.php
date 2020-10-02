<?php
//refillwhois.php
$pathboot = realpath(__DIR__ . "/../boot");
include("$pathboot/appbootstrap.php");

use \Ipblocker\Component\ComponentIpblocker;
(new ComponentIpblocker())->refill_whois();
