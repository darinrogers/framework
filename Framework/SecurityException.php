<?php
/**
 * SecurityException class
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
 * SecurityException class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class SecurityException extends \Framework\Exception
{
    /**
     * Constructory
     * 
     * @param string $publicMessage  Message displayed to end-user
     * @param string $privateMessage Message logged
     * 
     * @return null
     */
    public function __construct($publicMessage, $privateMessage)
    {
        error_log($privateMessage);
        parent::__construct($publicMessage);
    }
}