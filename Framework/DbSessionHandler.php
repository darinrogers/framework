<?php
/**
 * DbSessionHandler class
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
 * DbSessionHandler class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class DbSessionHandler
{
    /**
     * Reads the session
     * 
     * @param string $sessionId Session ID
     * 
     * @return string
     */
    public static function read($sessionId)
    {
        $sessionMapper = new \Mappers\SessionMapper();
        $session = $sessionMapper->findById($sessionId);
        
        return ($session) ? $session->getData() : array();
    }

    /**
     * Writes the session data
     * 
     * @param string $sessionId   Session ID
     * @param string $sessionData Serialized session data
     * 
     * @return null
     */
    public static function write($sessionId, $sessionData)
    {
    	$session = new \Models\Session();
    	$session->setId($sessionId);
        $session->setData($sessionData);
         
        $sessionMapper = new \Mappers\SessionMapper();
        $sessionMapper->update($session, \Mappers\SessionMapper::UPSERT);
    }
}