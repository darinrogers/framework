<?php

/**
 * Bootstrap file
 *
 * PHP version 5.3
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
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
    function ($name) {
        
        $file = FRAMEWORK_DIR . '/' . str_replace('\\', '/', $name) . '.php';
        
        if (file_exists($file)) {
            include $file;
        }
    }
);