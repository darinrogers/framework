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
    
    const ERROR_DUPLICATE_KEY = 11000;
    
    private $_collection = null;
    private $_database = null;
    private $_client = null;
    
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
        
        } catch (DbConnectionException $e) {
            
            throw new DbConnectionException(
                'Couldn\'t connect to the database', 
                $e->getCode(), 
                $e
            );
        }
    }
    
    protected function getConnectionString()
    {
    	$string = 'mongodb://' . $this->getHostNames();
    	
    	if ($this->getReplicaSetName() != '') {
    		$string .= '/?replicaSet=' . $this->getReplicaSetName();
    	}
    	
    	return $string;
    }
    
    protected function getReplicaSetName()
    {
    	return '';
    }
    
    protected function getClient()
    {
    	if ($this->_client == null) {
    		$this->_client = new \MongoClient(
    			$this->getConnectionString(), 
    			array('connectTimeoutMS' => 1000) // so it won't hang for one minute if a secondary is down
			);
    	}
    	
    	return $this->_client;
    }
    
    protected function getDb()
    {
    	if ($this->_database == null) {
    		
    		try {
    		
    			$this->_database = $this->getClient()->selectDB($this->getDbName());
    			
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
    abstract protected function getHostNames();
    
    /**
     * Returns an instance of the Model for this mapper
     * 
     * @param array $dataset The Dataset to instantiate with
     * 
     * @return null
     */
    abstract protected function getInstance(array $dataset);
    
    protected function convertToMongoId($id)
    {
    	return new \MongoId($id);
    }
    
    /**
     * Creates
     * 
     * @param \Models\Model $model The model
     * 
     * @return null
     */
    public function create(\Framework\Models\Model &$model)
    {
        $deltaData = $model->getDeltaDataset();
    	
    	$this->getCollection()->insert($deltaData);
    	
    	$model = $this->getInstance($deltaData);
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
    public function delete(\Framework\Models\Model $model)
    {
    	$data = $model->getDataset();
    	
    	$id = (isset($data['_id'])) ? $this->convertToMongoId($data['_id']) : null;
    	
    	$this->getCollection()->remove(
    		array('_id' => $id)
    	);
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
        $dataset = $this->getCollection()->findOne($criteria);
        
        if ($dataset != null) {
        	return $this->getInstance($dataset);
        }
        
        return null;
    }
    
    /**
     * Reads all objects from the collection
     * 
     * @return multitype:NULL 
     */
    public function readAll(array $criteria = array())
    {
        $allData = $this->getCollection()->find($criteria);
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
        
        $id = (isset($data['_id'])) ? $this->convertToMongoId($data['_id']) : null;
        
        if (isset($deltaData['_id'])) {
        	unset($deltaData['_id']);
        }
        
        $this->getCollection()->update(
            array('_id' => $id), 
            array('$set' => $deltaData), 
            $options
        );
    }
}