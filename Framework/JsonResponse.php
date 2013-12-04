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
class JsonResponse extends Response
{
    /**
     * Gets the resopnse as a string
     * 
     * @return string
     * 
     * @see \Framework\Response::getResponseAsString()
     */
    protected function getResponseAsString()
    {
        return json_encode($this->getVariables());
    }
    
    /**
     * Constructor 
     * 
     * @param string $controllerName The controller name
     * @param string $actionName     The action name
     * 
     * @return null
     */
    public function __construct($controllerName, $actionName)
    {    
        $this->addHeader('Content-type: application/json');
        
        parent::__construct($controllerName, $actionName);
    }
}