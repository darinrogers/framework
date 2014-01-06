<?php
/**
 * Cookie class
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
 * Cookie class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */

class Cookie
{
    /**
     * @var string
     */
    private $_name = '';
    /**
     * @var string
     */
    private $_path = '/';
    
    /**
     * Constructor
     * 
     * @param string $name The cookie name
     * 
     * @return null
     */
    public function __construct($name)
    {
        $this->_name = $name;
    }
    
    /**
     * Tells whether a cookie by the name given in the constructor exists
     * 
     * @return null
     */
    public function exists()
    {
        return isset($_COOKIE[$this->_name]);
    }
    
    /**
     * Gets the value of the cookie
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $_COOKIE[$this->_name];
    }
    
    /**
     * Sets the cookie on the user's browser
     * 
     * @param mixed $value The value to set
     * 
     * @return null
     * 
     * @throws Exception
     */
    public function setValue($value)
    {
        if (!setcookie($this->_name, $value, time() + Time::ONE_YEAR, $this->_path)) {
        
            throw new Exception(
                'Failed to set cookie named: ' . $this->_name,
                'We tried and failed to set a cookie in your browser. ' . 
                'Do you have them disabled?'
            );
        }
    }
    
    /**
     * Deletes a cookie
     * 
     *  @return null
     */
    public function delete()
    {
    	setcookie($this->_name, '', time() - Time::ONE_HOUR, $this->_path);
    }
}