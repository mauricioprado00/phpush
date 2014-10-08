<?php

class Phpush_Helper_PackageLib
{
    /**
     * returns all the class definitions of this lib
     * @return string
     */
    public static function packageLib()
    {
        $code = '';
        $dir = PHPUSH_PATH;
        $files = explode("\n", `find $dir -type f`);
        
        foreach ($files as $file) {
            if (file_exists($file)) {
                $code .= trim(substr(php_strip_whitespace($file), 5));
            }
        }
        
        $code .= ';Phpush_Remote::remoteHandle();';
        
        return $code;
    }
    
    public static function handleFinish()
    {
        return ';Phpush_Remote::remoteHandleFinish();die();';
    }
}