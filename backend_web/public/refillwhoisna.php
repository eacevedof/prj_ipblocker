<?php
//refillwhois.php
require __DIR__."/../vendor/autoload.php";

use Ipblocker\Providers\DbProvider;
(new DbProvider(""))->refill_whois_na();
