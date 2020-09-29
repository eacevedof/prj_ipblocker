<?php
//constants.php
$pathconfig = realpath(__DIR__ . "/../../../config");
$pathlogs = realpath(__DIR__ . "/../../../logs");
//echo is_dir($pathlogs);die($pathlogs);
define("IPB_PATH_CONFIG",$pathconfig);
define("IPB_PATH_LOGS",$pathlogs);

//=========================
//        FLAGS
//=========================
define("IPB_ENABLE_LOGS",1);
define("IPB_DOTRACK",1);