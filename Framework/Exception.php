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
     * @param string $privateMessage Private message, typically used for logging
     * @param string $publicMessage  Public message, typically used to show to the user
     * 
     * @return null
     */
    public function __construct($privateMessage, $publicMessage)
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