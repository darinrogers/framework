<?php
/**
 * Layout class
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
 * Layout class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class Layout
{
    /**
     * @var \Framework\Layout
     */
    private static $_instance = null;
    /**
     * @var array
     */
    private $_metaTags = array();
    /**
     * @var array
     */
    private $_variables = array();
    
    private $_name = 'default';
    
    private $_styleSheets = array();
    
    /**
     * Constructor
     * 
     * @return null
     */
    private function __construct()
    {
        
    }
    
    /**
     * Adds a meta tag to the layout
     * 
     * @param string $name    Name
     * @param string $content Content
     * 
     * @return null
     */
    public function addMetaTag($name, $content)
    {
        $this->_metaTags[] = array($name, $content);
    }
    
    public function addStyleSheet($href)
    {
    	$this->_styleSheets[] = $href;
    }
    
    /**
     * Gets a singleton Layout instance
     * 
     * @return \Framework\Layout
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Layout();
        }
        
        return self::$_instance;
    }
    
    /**
     * Gets a variable
     * 
     * @param string $name Name
     * 
     * @return multitype:
     */
    public function get($name)
    {
        return (isset($this->_variables[$name]))
            ? $this->_variables[$name]
            : '';
    }
    
    /** 
     * Gets all meta tags
     * 
     * @return array
     */
    public function getMetaTags()
    {
        return $this->_metaTags;
    }
    
    public function getStyleSheets()
    {
    	return $this->_styleSheets;
    }
    
    /**
     * Renders the layout
     * 
     * @return null
     */
    public function render()
    {
        foreach ($this->_variables as $name => $value) {
            $$name = $value;
        }
        
        include APP_DIR . '/layouts/' . $this->_name . '.php';
    }
    
    /**
     * Sets a layout variable
     * 
     * @param string    $name  Name
     * @param multitype $value Value
     * 
     * @return \Framework\Layout
     */
    public function set($name, $value)
    {
        $this->_variables[$name] = $value;
        return $this;
    }
    
    /**
     * Sets the name of the layout file
     * 
     * @param string $name The name
     * 
     * @return null
     */
    public function setName($name)
    {
        $this->_name = $name;
    }
}