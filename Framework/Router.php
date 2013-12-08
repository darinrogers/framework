<?php
/**
 * Router class
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
 * Router class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class Router
{
    /**
     * @var unknown
     */
    private $_actionName = '';
    /**
     * @var unknown
     */
    private $_controllerName = '';
    
    /**
     * Constructor
     * 
     * @param string $requestUri Request URI
     * 
     * @return null
     */
    public function __construct($requestUri)
    {
        $requestWithQuerystring = explode('?', $requestUri);
        $requestUri = $requestWithQuerystring[0];
    	
    	$requestParts = explode('/', substr($requestUri, 1));
        
        if ($requestParts[0] != '') {
            
            $this->_controllerName = str_replace(
                ' ', 
                '', 
                ucwords(
                    str_replace(
                        '-', 
                        ' ', 
                        $requestParts[0]
                    )
                )
            );
        
        } else {
            
            $this->_controllerName = 'Index';
        }
        
        // index 1 would be set to empty after rewriting with trailing slash
        if (isset($requestParts[1]) && $requestParts[1] !== '') {
            
            $this->_actionName = lcfirst(
                str_replace(
                    ' ', 
                    '', 
                    ucwords(
                        str_replace(
                            '-', 
                            ' ', 
                            $requestParts[1]
                        )
                    )
                )
            );
        
        } else {
            
            $this->_actionName = 'index';
        } 
    }
    
    /**
     * Gets the action name
     * 
     * @return string
     */
    public function getActionName()
    {
        return $this->_actionName;
    }
    
    /**
     * Gets the controller name
     * 
     * @return string
     */
    public function getControllerName()
    {
        return $this->_controllerName;
    }
}