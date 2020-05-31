<?php
//ipblocker.php
$pathlib = realpath(__DIR__."/../lib");
//die("pathlib:$pathlib");
include("$pathlib/functions.php");
include("$pathlib/helper_request.php");
include("$pathlib/component_config.php");
include("$pathlib/component_mysql.php");
include("$pathlib/component_mailing.php");
include("$pathlib/provider_base.php");
include("$pathlib/component_ipblocker.php");

$o = new \TheFramework\Components\ComponentIpblocker();
$o->handle_request();
