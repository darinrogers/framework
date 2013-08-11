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
    private $_post = null;
    
    /**
     * Lazily loads the POST parameters, defaulting to $_POST
     * 
     * @return Ambigous <unknown, array>
     */
    private function _getPost()
    {
        if ($this->_post === null) {
            $this->_post = $_POST;
        }
        
        return $this->_post;
    }
    
    /**
     * Starts a session only if one isn't already started
     * 
     * @return null
     */
    private function _startSession()
    {
        if (session_id() === '') {
            session_start();
        }
    }
    
    /**
     * Gets a POST parameter
     * 
     * @param string $parameterName Parameter name
     * 
     * @return Ambigous <>
     */
    protected function getPost($parameterName)
    {
        $post = $this->_getPost();
        return $post[$parameterName];
    }
    
    /**
     * Sets the POST parameters
     * 
     * @param array $post The POST
     * 
     * @return null
     */
    protected function setPost(array $post)
    {
        $this->_post = $post;
    }
    
    /**
     * Called before calling ...Action
     * 
     * @return null
     */
    protected function onBeforeDispatch()
    {
        // no op
    }
    
    /**
     * Gets a CSRF token. Starts a session if one isn't already started. 
     * 
     *  @return string
     */
    protected function getCsrfToken()
    {
        $this->_startSession();
        
        if (!isset($_SESSION['csrfToken'])) {
        
            $csrfToken = new \Framework\CsrfToken();
            $_SESSION['csrfToken'] = (string)$csrfToken;
        }
        
        return $_SESSION['csrfToken'];
    }
    
    protected function validateCsrfToken($csrfToken)
    {
        
    }
    
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
        
        $this->onBeforeDispatch();
        
        call_user_func(array($this, $actionName . 'Action'));
    }
}