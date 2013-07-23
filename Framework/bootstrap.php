<?php
/**
 * 
 */

/**
 * @var unknown
 */
define('FRAMEWORK_DIR', realpath(__DIR__ . '/../'));

spl_autoload_register(
    /**
    * Autoloads framework files
    * 
    * @param string $name Class name
    * 
    * @return null
    */
    function($name) {
        
        $file = FRAMEWORK_DIR . '/' . str_replace('\\', '/', $name) . '.php';
        
        if (file_exists($file)) {
            require $file;
        }
    }
);