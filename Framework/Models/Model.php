<?php
/**
 * Model class
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
namespace Framework\Models;

/**
 * Model class
 * 
 * @category Gravatonic
 * @package  Gravatonic
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/framework
 */
abstract class Model
{
    /**
     * @var unknown
     */
    private $_originalData = array();
    /**
     * @var unknown
     */
    private $_dataset = array();
    
    /**
     * Gets a property
     * 
     * @param string $name Name of the property
     * 
     * @return mixed|null
     */
    protected function getProperty($name)
    {
        return (isset($this->_dataset[$name]))
            ? $this->_dataset[$name]
            : null;
    }
    
    /**
     * Sets a property
     * 
     * @param mixed $name  The property name
     * @param mixed $value The property value
     * 
     * @return null
     */
    protected function setProperty($name, $value)
    {
        $this->_dataset[$name] = $value;
        $this->_dataset = array_merge(
            $this->_originalData, 
            $this->_dataset
        );
    }
    
    /**
     * Constructor
     * 
     * @param array $dataset Dataset to initialize with
     * 
     * @return null
     */
    public function __construct(array $dataset = null)
    {
        if ($dataset !== null) {
            
            $this->_originalData = $dataset;
            $this->_dataset = $dataset;
        }
    }
    
    /**
     * Gets delta data
     * 
     * @return multitype:
     */
    public function getDeltaDataset()
    {
        return array_diff_assoc($this->_dataset, $this->_originalData);
    }
    
    /**
     * Gets the entire dataset
     * 
     * @return array
     */
    public function getDataset()
    {
        return $this->_dataset;
    }
}