<?php 
/**
 * BitArray class
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
 * BitArray class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
class BitArray
{
    /**
     * @var number
     */
    private $_value = 0;
    
    /**
     * Gets the bit value at the given position
     * 
     * @param number $position Position of the bit to get, starting at 1
     * 
     * @return string
     */
    private function _getBit($position)
    {
        $bitString = strrev(decbin($this->_value));
        
        return (isset($bitString[$position - 1])) ? $bitString[$position - 1] : '0';
    }
    
    /**
     * Sets the bit value at the given position
     * 
     * @param number $position Position of the bit to set the value for, starting at 1
     * @param number $value    Value to set the bit to, either 1 or 0
     * 
     * @return null
     */
    private function _setBit($position, $value)
    {
        $bitString = strrev(decbin($this->_value));
        
        $bitString[$position - 1] = $value;
        
        $this->_value = bindec(strrev($bitString));
    }
    
    /**
     * Constructor
     * 
     * @param number $value Initial value
     * 
     * @return null
     */
    public function __construct($value)
    {
        $this->_value = $value;
    }
    
    /**
     * Gets the current value of the bit array
     * 
     * @return number
     */
    public function getValue()
    {
        return $this->_value;
    }
    
    /**
     * Tells whether or not the bit at the given position is set
     * 
     * @param number $position Position of the bit to inspect
     * 
     * @return boolean
     */
    public function isBitSetAt($position)
    {
        return ($this->_getBit($position) === '1') ? true : false;
    }
    
    /**
     * Toggles the bit at the given position. If it's 1, sets to 0 and vice-versa.
     * 
     * @param number $position The position of the bit to toggle
     * 
     * @return null
     */
    public function toggleBitAt($position)
    {
        $this->_setBit($position, ($this->_getBit($position) === '1') ? '0' : '1');
    }
    
    /**
     * Sets the bit at the given position to 0
     * 
     * @param number $position Position of the bit to turn off, 1-based
     * 
     * @return null
     */
    public function turnOffBitAt($position)
    {
        $this->_setBit($position, '0');
    }
    
    /**
     * Sets the bit at the given position to 1
     * 
     * @param number $position Position of the bit to turn on, 1-based
     * 
     * @return null
     */
    public function turnOnBitAt($position)
    {
        $this->_setBit($position, '1');
    }
}

?>