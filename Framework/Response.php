<?php
/**
 * Response class
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
 * @author darin
 *
 */
namespace Framework;

/**
 * Response class 
 *  
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class Response
{
    /**
     * @var unknown
     */
    private $_controllerName = '';
    /**
     * @var unknown
     */
    private $_actionName = '';
    
    /**
     * Constructor
     * 
     * @param string $controllerName Controller name
     * @param string $actionName     Action name
     * 
     * @return null
     */
    public function __construct($controllerName, $actionName)
    {
        $this->_controllerName = $controllerName;
        $this->_actionName = $actionName;
    }
    
    /**
     * Converts this response object to a string
     * 
     * @return string
     */
    public function __toString()
    {
        ob_start();
        
        include APP_DIR . '/views/' . $this->_controllerName . '/' . 
            $this->_actionName . '.php';
        
        $viewContent = ob_get_contents();
        ob_clean();
        
        include APP_DIR . '/layouts/default.php';
        
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
}