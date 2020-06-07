<?php
//constants.php
define("IPB_ENABLE_LOGS",1);
$pathlib = realpath(__DIR__."/../lib");
$pathconfig = realpath(__DIR__."/../../config");
$pathlogs = realpath(__DIR__."/../../logs");
//print_r($pathlogs);die;
define("IPB_PATH_CONFIG",$pathconfig);
define("IPB_PATH_LOGS",$pathlogs);
define("IPB_PATH_LIB",$pathlib);
