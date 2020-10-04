<?php
//ipblocker.php
require __DIR__."/../vendor/autoload.php";
//use function Ipblocker\Functions\pp;
use Ipblocker\Controllers\ControllerMain;

if(defined("IPB_DOTRACK") && IPB_DOTRACK) {
    $ipb_f = (new ControllerMain); $ipb_f(); unset($ipb_f);
}
//pp(get_included_files(),"index.php get_included_files");
