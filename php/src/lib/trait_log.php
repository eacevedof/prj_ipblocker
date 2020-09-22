<?php
namespace Theframework\Traits;
use Theframework\Components\ComponentLog As L;

trait TraitLog
{
    protected function log($mxVar,$sTitle=NULL)
    {
        if(!$this->is_logactive()) return;
        $pathlogs = realpath(IPB_PATH_LOGS);
        $oLog = new L("sql",$pathlogs);
        $oLog->save($mxVar,$sTitle);
    }

    protected function logd($mxVar,$sTitle=NULL)
    {
        if(!$this->is_logactive()) return;
        $pathlogs = realpath(IPB_PATH_LOGS);
        $oLog = new L("debug",$pathlogs);
        $oLog->save($mxVar,$sTitle);
    }

    protected function logpost()
    {
        if(!$this->is_logactive()) return;
        $this->logd($_POST,"POST");
    }

    protected function logget()
    {
        if(!$this->is_logactive()) return;
        $this->logd($_GET,"GET");
    }

    protected function logreq()
    {
        if(!$this->is_logactive()) return;
        $this->logd($_REQUEST,"REQUEST");
    }

    private function is_logactive()
    {
        return (defined("IPB_ENABLE_LOGS") && IPB_ENABLE_LOGS);
    }

}//AppLogTrait