<?php

define('PHPUSH_PATH', __DIR__ . '/classes/');

spl_autoload_register( 'phpush_autoload' );
function phpush_autoload($class ) {
	if (0 === strpos($class, 'Phpush_')) {

        $file = PHPUSH_PATH . str_replace( '_', '/', substr($class, strlen('Phpush_')) ) . '.php';

        if (!file_exists($file)) {
            error_log("cant load the class $class\n");
        }
        require_once($file);
    } 
}
