<?php
//constants.php
define("IPB_ENABLE_LOGS",0);
define("IPB_DOTRACK",1);
$pathconfig = realpath(__DIR__ . "/../../../config");
$pathlogs = realpath(__DIR__ . "/../../../logs");
//echo is_dir($pathlogs);die($pathlogs);
define("IPB_PATH_CONFIG",$pathconfig);
define("IPB_PATH_LOGS",$pathlogs);