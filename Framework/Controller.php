<?php
/**
 * Controller class
 *
 * PHP version 5.3
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */

namespace Framework;

/**
 * Controller class 
 *  
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
abstract class Controller
{
    private $_calledAction = '';
    /**
     * @var \Framework\Response
     */
    private $_response = null;
    
    /**
     * Gets the response object
     * 
     * @return \Framework\Response
     */
    public function getResponse()
    {
        if ($this->_response === null) {
            
            $qualifiedClassParts = explode('\\', get_called_class());
            
            $this->_response = new Response(
                end($qualifiedClassParts), 
                $this->_calledAction
            );
        }
        
        return $this->_response;
    }
    
    /**
     * Runs the controller action
     * 
     * @param string $actionName Action name
     * 
     * @return null
     */
    public function runAction($actionName)
    {
        $this->_calledAction = $actionName;
        
        call_user_func(array($this, $actionName . 'Action'));
    }
}