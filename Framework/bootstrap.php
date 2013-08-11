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
if (!defined('FRAMEWORK_DIR')) {
    define('FRAMEWORK_DIR', realpath(__DIR__ . '/../'));
}

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

spl_autoload_register(function ($name) {

    $file = APP_DIR . '/' . str_replace('\\', '/', $name) . '.php';

    if (file_exists($file)) {
        include $file;
    }
}
);

session_set_save_handler(
    function ($savePath, $sessionName) {
        // open
        return true;
    },
    function () {
        // close
        return true;
    },
    '\Framework\DbSessionHandler::read',
    '\Framework\DbSessionHandler::write',
    function ($sessionId) {
        // destroy
        return true;
    },
    function ($lifetime) {
        // gc
        return true;
    }
);

register_shutdown_function('session_write_close');

require __DIR__ . '/helpers.php';