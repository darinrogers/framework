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
     * Gets the response object
     * 
     * @return \Framework\Response
     */
    public function getResponse()
    {
        $qualifiedClassParts = explode('\\', get_called_class());
        
        return new Response(
            end($qualifiedClassParts), 
            $this->_calledAction
        );
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