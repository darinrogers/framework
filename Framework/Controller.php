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
    private $_viewName = '';
    
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
        	
        	if (php_sapi_name() !== 'cli') {

        		session_start();
        	
        	} else {
        		
        		// if we're on the command line (i.e. unit tests), create global
        		if (!isset($_SESSION)) {
        			
        			global $_SESSION;
        			$_SESSION = array();
        		}
        	}
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
        return (isset($post[$parameterName]))
            ? $post[$parameterName]
            : '';
    }
    
    /**
     * Pops a basic authentication dialog
     * 
     * @param string $realm    Authentication realm
     * @param string $username Username
     * @param string $password Password
     * 
     * @return null
     */
    protected function requireBasicAuth($realm, $username, $password)
    {
        $enteredUser = (isset($_SERVER['PHP_AUTH_USER'])) 
            ? $_SERVER['PHP_AUTH_USER']
            : '';
        $enteredPass = (isset($_SERVER['PHP_AUTH_PW']))
            ? $_SERVER['PHP_AUTH_PW']
            : '';
        
        if ($enteredUser != $username || $enteredPass != $password) {
            
            header('WWW-Authenticate: Basic realm="' . $realm . '"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'You must authenticate!';
            exit;
        } 
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
    
    protected function onCreatedCsrfToken($csrfToken)
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
        if (!isset($_SESSION['csrfToken'])) {
        
            $csrfToken = new \Framework\CsrfToken();
            $_SESSION['csrfToken'] = (string)$csrfToken;
            
            $this->onCreatedCsrfToken((string)$csrfToken);
        }
        
        return $_SESSION['csrfToken'];
    }
    
    /**
     * Validates a CSRF token
     * 
     * @param string $csrfToken CSRF token
     * 
     * @return null
     * 
     * @throws \Framework\SecurityException
     */
    protected function validateCsrfToken($csrfToken)
    {
        if ($csrfToken !== $this->getCsrfToken()) {
            
            throw new \Framework\SecurityException(
                'There was a security error.', 
                'CSRF exception: ' . $csrfToken . ' didn\'t match expected.'
            );
        }
    }
    
    /**
     * Sets the name of the layout file, e.g. default
     * 
     * @param string $layoutName The layout name
     * 
     * @return null
     */
    protected function setLayoutName($layoutName)
    {
        \Framework\Layout::getInstance()->setName($layoutName);
    }
    
    protected function setViewName($viewName)
    {
    	$this->_viewName = $viewName;
    	
    	if ($this->_response !== null) {
    		$this->_response->setViewName($viewName);
    	}
    }
    
    /**
     * Gets the response class name, defaulting to Reponse
     * 
     * @return string
     */
    protected function getResponseClassName()
    {
        return 'Response';
    }
    
    protected function getCalledControllerName()
    {
    	$qualifiedClassParts = explode('\\', get_called_class());
    	return end($qualifiedClassParts);
    }
    
    public function __construct()
    {
    	$this->_startSession();
    }
    
    /**
     * Gets the response object
     * 
     * @return \Framework\Response
     */
    public function getResponse()
    {
        if ($this->_response === null) {
            
            $responseClassName = '\\Framework\\' . $this->getResponseClassName();
            
            $this->_response = new $responseClassName(
                $this->getCalledControllerName(), 
                $this->_calledAction
            );
            
            // if we're not using the default view
            if ($this->_viewName != '') {
            	$this->_response->setViewName($this->_viewName);
            }
            
            if (php_sapi_name() === 'cli') {
            	$this->_response->setSendHeaders(false);
            }
        }
        
        return $this->_response;
    }
    
    public function hasAction($actionName)
    {
    	return method_exists($this, $actionName . 'Action');
    }
    
    /**
     * Runs the controller action
     * 
     * @param string $actionName Action name
     * 
     * @return null
     */
    public function runAction($actionName, $get = null)
    {
        if ($get !== null) {
        	$_GET = $get;
        }
    	
    	$this->_calledAction = $actionName;
        
        $this->onBeforeDispatch();
        
        call_user_func(array($this, $actionName . 'Action'));
    }
}