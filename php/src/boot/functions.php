<?php
//functions.php
function pp($mxvar,$title="",$die=0)
{
    $str = var_export($mxvar,1);
    if($title) echo "\n$title:";
    echo "\n$str";
    if($die) die("\n==die==");
}