<?php
/**
 * CsrfToken class
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
 * CsrfToken class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class CsrfToken
{
    /**
     * @var string
     */
    private $_string = '';
    
    /**
     * Constructor
     * 
     * @return null
     */
    public function __construct()
    {
        $this->_string = md5(microtime());
    }
    
    /**
     * Magic method for casting to a string
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->_string;
    }
}