<?php
/**
 * Exception class
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
 * Exception class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class Exception extends \Exception
{
    /**
     * @var string
     */
    private $_privateMessage = '';
    
    /**
     * Constructor
     * 
     * @param string $publicMessage  Private message, typically used for logging
     * @param string $privateMessage Public message, used to show to the user
     * 
     * @return null
     */
    public function __construct($publicMessage, $privateMessage = '')
    {
        $this->_privateMessage = $privateMessage;
        
        parent::__construct($publicMessage);
    }
    
    /**
     * Gets the private message
     * 
     * @return string
     */
    public function getPrivateMessage()
    {
        return $this->_privateMessage;
    }
}