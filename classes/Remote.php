<?php

class Phpush_Remote
{
    public static function remoteHandle()
    {
        header('content-type: text/plain');
        ini_set("memory_limit", "250M");
        $time = 60 * 60 * 5; //5 horas
        ini_set('max_execution_time', $time);
        Phpush_Helper_Updown::remoteHandle();
    }
    
    public static function remoteHandleFinish()
    {
        Phpush_Helper_Updown::remoteHandleFinish();
    }
}