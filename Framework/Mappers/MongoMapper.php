<?php
/**
 * MongoMapper class
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
namespace Framework\Mappers;

/**
 * MongoMapper class
 * 
 * @category Framework
 * @package  Framework
 * @author   Darin Rogers <darinrogers@hotmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @link     https://github.com/darinrogers/fravatonic
 */
abstract class MongoMapper
{
    const UPSERT = true;
    
    private $_collection = null;
    private $_database = null;
    
    /**
     * Gets the collection. 
     * 
     * @throws \Mappers\DbConnectionException
     * 
     * @return MongoCollection
     */
    protected function getCollection()
    {
        if ($this->_collection !== null) {
            return $this->_collection;
        }
        
        try {
            
            $this->_collection = $this->getDb()->selectCollection($this->getCollectionName());
            
            return $this->_collection;
        
        } catch (\Exception $e) {
            
            throw new DbConnectionException(
                'Couldn\'t select the database collection.', 
                $e->getCode(), 
                $e
            );
        }
    }
    
    protected function getDb()
    {
    	if ($this->_database == null) {
    		
    		try {
    		
    			$client = new \MongoClient('mongodb://' . $this->getHostname() . ':27017');
    			$this->_database = $client->selectDB($this->getDbName());
    			
    		} catch (\MongoConnectionException $e) {
    			
    			throw new DbConnectionException(
    				'Couldn\'t connect to the database.',
    				$e->getCode(),
    				$e
    			);
    		}
    	}
    	
    	return $this->_database;
    }
    
    /**
     * Sets the collection. Used mainly for dependency injection. 
     * 
     * @param \MongoCollection $collection The collection
     * 
     * @return null
     */
    protected function setCollection(\MongoCollection $collection)
    {
        $this->_collection = $collection;
    }
    
    /**
     * Gets the collection name 
     * 
     * @return string
     */
    abstract protected function getCollectionName();
    
    /**
     * Gets the DB name
     * 
     * @return string
     */
    abstract protected function getDbName();
    
    /**
     * Gets the host name
     * 
     * @return string
     */
    abstract protected function getHostName();
    
    /**
     * Returns an instance of the Model for this mapper
     * 
     * @param array $dataset The Dataset to instantiate with
     * 
     * @return null
     */
    abstract protected function getInstance(array $dataset);
    
    /**
     * Creates
     * 
     * @param \Models\Model $model The model
     * 
     * @return null
     */
    public function create(\Framework\Models\Model $model)
    {
        $this->getCollection()->insert($model->getDeltaDataset());
    }
    
    public function batchInsert(array $models, array $options = array())
    {
    	$modelArrays = array();
    	$deltaData = array();
    	
    	/* @var $model \Framework\Models\Model */
    	foreach ($models as $model) {
    		
    		$deltaData = $model->getDeltaDataset();
    		
    		if (count($deltaData) == 0) {
    			continue;
    		}
    		
    		$modelArrays[] = $deltaData;
    	}
    	
    	// TODO hacky workaround for batch insert not continuing on error
    	//$this->getCollection()->batchInsert($modelArrays, $options);
    	foreach ($modelArrays as $modelArray) {
    		try {
    			$this->getCollection()->insert($modelArray, $options);
    		} catch (\Exception $e) {
    			//...
    		}
    	}
    }
    
    /**
     * Deletes
     * 
     * @param \Models\Model $model The model
     * 
     * @return null
     */
    public function delete(\Models\Model $model)
    {
        
    }
    
    /**
     * Reads one object
     * 
     * @param array $criteria The criteria
     * 
     * @return multitype
     */
    public function readOne(array $criteria)
    {
        return $this->getCollection()->findOne($criteria);
    }
    
    /**
     * Reads all objects from the collection
     * 
     * @return multitype:NULL 
     */
    public function readAll()
    {
        $allData = $this->getCollection()->find();
        $allObjects = array();
        
        foreach ($allData as $datum) {
            $allObjects[] = $this->getInstance($datum);
        }
        
        return $allObjects; 
    }
    
    /**
     * Updates
     * 
     * @param \Models\Model $model  The mode
     * @param bool          $upsert Whether to upsert or not. Use MongoMapper::UPSERT
     * 
     * @return null
     */
    public function update(\Framework\Models\Model $model, $upsert = false)
    {
        $options = array();
        
        if ($upsert) {
            $options['upsert'] = true;
        }
        
        $deltaData = $model->getDeltaDataset();
        $data = $model->getDataset();
        
        $this->getCollection()->update(
            array('_id' => $data['_id']), 
            $deltaData, 
            $options
        );
    }
}