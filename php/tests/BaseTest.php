<?php
namespace Tests;

$pathboot = realpath(__DIR__."/../boot");
include("$pathboot/appbootstrap.php");

abstract class BaseTest
{

    public abstract function run();
}