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
class Response implements \ArrayAccess
{
    /**
     * @var string
     */
    private $_controllerName = '';
    /**
     * @var string
     */
    private $_actionName = '';
    /**
     * @var array
     */
    private $_variables = array();
    
    private $_isViewEnabled = true;
    private $_isLayoutEnabled = true;
    private $_headers = array();
    private $_sendHeaders = true;
    private $_statusCode = 200;
    
    /**
     * Gets all the variables set
     * 
     * @return array
     */
    protected function getVariables()
    {
        return $this->_variables;
    }
    
    /**
     * Returns the response as a string
     * 
     * @return string
     */
    protected function getResponseAsString()
    {
        foreach ($this->_variables as $name =>  $value) {
            $$name = $value;
        }
        
        ob_start();
        
        $viewContent = '';
        
        if ($this->_isViewEnabled) {
        
            $viewFileName = APP_DIR . '/views/' . $this->_controllerName . '/' .
                    $this->_actionName . '.php';
        	
            if (!file_exists($viewFileName)) {
                die('View file missing: ' . $viewFileName);
            }
        
            include $viewFileName;
        
            $viewContent = ob_get_contents();
            ob_clean();
        
            $scriptFileName = APP_DIR . '/views/' . $this->_controllerName . '/' .
                    $this->_actionName . '.script.php';
            $scriptContent = '';
        
            if (file_exists($scriptFileName)) {
                 
                include $scriptFileName;
                $scriptContent = ob_get_contents();
                ob_clean();
            }
        }
        
        if ($this->_isLayoutEnabled) {
        
            \Framework\Layout::getInstance()
            ->set('viewContent', $viewContent)
            ->set('controllerName', $this->_controllerName)
            ->set('actionName', $this->_actionName)
            ->set('scriptContent', $scriptContent)
            ->render();
        }
        
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
    }
    
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
     * Adds a header to the response
     * 
     * @param string $header The header
     * 
     * @return null
     */
    public function addHeader($header)
    {
        $this->_headers[] = $header;
    }
    
    /**
     * Sets the HTTP response status code
     * 
     * @param int $code Status code
     * 
     * @return null
     */
    public function setStatusCode($code)
    {
        $this->_statusCode = $code;
    }
    
    public function getStatusCode()
    {
    	return $this->_statusCode;
    }
    
    /**
     * Converts this response object to a string
     * 
     * @return string
     */
    public function __toString()
    {
        if ($this->_sendHeaders) {
	    	
        	foreach ($this->_headers as $header) {
	            header($header);
	        }
	        
	        if ($this->_statusCode != 200) {
	        	header('Status: ' . $this->_statusCode, true, $this->_statusCode);
	        }
        }
        
        return $this->getResponseAsString();
    }
    
    /**
     * Disables view rendering
     * 
     *  @return null
     */
    public function disableView()
    {
        $this->_isViewEnabled = false;
        $this->_isLayoutEnabled = false;
    }
    
    /** 
     * Tells whether an offset exists
     * 
     * @param mixed $offset The offset to check for
     * 
     * @return bool
     * 
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->_variables[$offset]);
    }
    
    /** 
     * Gets an offset
     * 
     * @param mixed $offset The offset to get
     * 
     * @return mixed
     * 
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->_variables[$offset])
            ? $this->_variables[$offset]
            : null;
    }
    
    /** 
     * Sets an offset
     * 
     * @param mixed $offset The offset to set
     * @param mixed $value  The value to set the offset to
     * 
     * @return null
     * 
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            
            $this->_variables[] = $value;
        
        } else {
            
            $this->_variables[$offset] = $value;
        }
    }
    
    /** 
     * Unsets an offset
     * 
     * @param mixed $offset The offset to unset
     * 
     * @return null
     * 
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->_variables[$offset]);
    }
    
    public function redirect($url)
    {
    	header('Location: ' . $url);
    	exit;
    }
    
    public function setSendHeaders($sendHeaders)
    {
    	$this->_sendHeaders = $sendHeaders;
    }
}