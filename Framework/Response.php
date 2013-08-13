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
        }
        
        if ($this->_isLayoutEnabled) {
            
            \Framework\Layout::getInstance()
                ->set('viewContent', $viewContent)
                ->render();
        }
        
        $output = ob_get_contents();
        ob_end_clean();
        
        return $output;
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
}