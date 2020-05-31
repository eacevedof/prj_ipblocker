<?php
//ipblocker.php
$pathlib = realpath(__DIR__."/../lib");
$pathconfig = realpath(__DIR__."/../../config");
//define("IPB_PATH_LIB",$pathlib);
define("IPB_PATH_CONFIG",$pathconfig);
//die(PATH_LIB);
//die("pathlib:$pathlib");
include("$pathlib/functions.php");
include("$pathlib/component_log.php");
include("$pathlib/trait_log.php");
include("$pathlib/helper_request.php");
include("$pathlib/component_config.php");
include("$pathlib/component_mysql.php");
include("$pathlib/component_mailing.php");
include("$pathlib/provider_base.php");
include("$pathlib/component_ipblocker.php");

$o = new \TheFramework\Components\ComponentIpblocker();
$o->handle_request();
