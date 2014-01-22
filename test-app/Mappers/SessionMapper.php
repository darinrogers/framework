<?php

namespace Mappers;

class SessionMapper extends \Framework\Mappers\MongoMapper
{
	protected function getCollectionName()
	{
		return 'session';
	}
	
	protected function getInstance(array $dataset = null)
	{
		return new \Models\Session($dataset);
	}
	
	protected function getDbName()
	{
		return 'framework-test';
	}
	
	protected function getHostNames()
	{
		return 'localhost';
	}
	
	public function findById($id)
	{
		$sessionData = parent::readOne(array('_id' => $id));
		
		return $this->getInstance($sessionData);
	}
}