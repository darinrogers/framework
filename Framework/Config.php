<?php
/**
 * Config class
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
 * Config class 
 *  
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class Config implements \ArrayAccess
{
    private $_environment = '';
    private $_config = array();
    private $_isParsed = false;
    /**
     * @var \Framework\Config
     */
    private static $_instance = null;
    
    /**
     * Gets the config
     * 
     * @return multitype:
     */
    private function _getConfig()
    {
        if (!$this->_isParsed) {
            
            $environment = $this->_getEnvironment();
            
            foreach ($this->_config as $section => $data) {
                
                if ($section == $environment) {
                    
                    $this->_config = $data;
                    break;
                
                } elseif (strpos($section, $environment . ':') === 0) {
                    
                    $hierarchy = explode(':', $section);
                    
                    $this->_config = array_merge(
                        $this->_config[$hierarchy[1]], 
                        $this->_config[$hierarchy[0] . ':' . $hierarchy[1]]
                    );
                    
                    $this->_isParsed = true;
                    break;
                }
            }
        }
        
        return $this->_config;
    }
    
    /**
     * Gets the environment
     * 
     * @return string
     */
    private function _getEnvironment()
    {
        if ($this->_environment == '') {
            $this->_environment = getenv('APP_ENV');
        }
        
        return $this->_environment;
    }
    
    /**
     * Sets the environment name
     * 
     * @param string $environment Environment
     * 
     * @return null
     */
    protected function setEnvironment($environment)
    {
        $this->_environment = $environment;
    }
    
    /**
     * Constructor
     * 
     * @return null
     */
    protected function __construct()
    {
        $this->_config = parse_ini_file(
            APP_DIR . '/configs/main.ini', 
            true
        );
    }
    
    /**
     * Gets a singleton instance
     * 
     * @return \Framework\Config
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \Framework\Config();
        }
        
        return self::$_instance;
    }
    
    /**
     * Checks if an offset exists
     * 
     * @param mixed $offset Offset
     * 
     * @return bool
     * 
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        $config = $this->_getConfig();
        
        return isset($config[$offset]);
    }
    
    /**
     * Offset get
     * 
     * @param mixed $offset Offset
     * 
     * @return mixed
     */
    public function offsetGet($offset)
    {
        $config = $this->_getConfig();
        
        return (isset($config[$offset])) ? $config[$offset] : null;
    }
    
    /**
     * Offset set
     * 
     * @param mixed $offset Offset
     * @param mixed $value  Value
     * 
     * @return null
     * 
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $config = $this->_getConfig();
        
        if (is_null($offset)) {
            
            $config[] = $value;
        
        } else {
            
            $config[$offset] = $value;
        }
    }
    
    /**
     * Offset unset
     * 
     * @param mixed $offset Offset
     * 
     * @return null
     * 
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        $config = $this->_getConfig();
        
        unset($config[$offset]);
    }
    
    /**
     * Gets a config parameter
     * 
     * @param string $parameter Parameter name
     * 
     * @return \Framework\Config
     */
    public function get($parameter)
    {
        return $this[$parameter];
    }
}