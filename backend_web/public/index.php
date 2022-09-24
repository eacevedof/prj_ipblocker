<?php
//ipblocker.php

require __DIR__."/../vendor/autoload.php";
//use function Ipblocker\Functions\pp;
$tz = date_default_timezone_get();
date_default_timezone_set($tz);
use Ipblocker\Controllers\ControllerMain;

if(defined("IPB_DOTRACK") && IPB_DOTRACK) {
    $ipbfn = (new ControllerMain); $ipbfn(); unset($ipbfn);
}
//pp(get_included_files(),"index.php get_included_files");
