<?php

namespace Models;

class Session extends \Framework\Models\Model
{
	public function getData()
	{
		return $this->getProperty('data');
	}
	
	public function setId($id)
	{
		$this->setProperty('_id', $id);
	}
	
	public function setData($data)
	{
		$this->setProperty('data', $data);
	}
}