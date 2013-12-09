<?php

namespace Framework\Test;

class TestableController extends \Framework\Controller
{
	public function __construct()
	{
		// I'm just here to make sure we don't call session_start() in the parent
	}
	
	protected function someAction()
	{
		
	}
	
	public function validateCsrfToken($csrfToken)
	{
	    parent::validateCsrfToken($csrfToken);
	}
}