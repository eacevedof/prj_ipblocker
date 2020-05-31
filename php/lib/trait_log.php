<?php
namespace Theframework\Traits;
use Theframework\Components\ComponentLogLog As L;

trait TraitLog
{
    protected function log($mxVar,$sTitle=NULL)
    {
        $pathlogs = realpath(IPB_PATH_LOGS);
        $oLog = new L("sql",$pathlogs);
        $oLog->save($mxVar,$sTitle);
    }

    protected function logd($mxVar,$sTitle=NULL)
    {
        $pathlogs = realpath(IPB_PATH_LOGS);
        $oLog = new L("debug",$pathlogs);
        $oLog->save($mxVar,$sTitle);
    }

    protected function logpost()
    {
        $this->logd($_POST,"POST");
    }

    protected function logget()
    {
        $this->logd($_GET,"GET");
    }

    protected function logreq()
    {
        $this->logd($_REQUEST,"REQUEST");
    }

}//AppLogTrait