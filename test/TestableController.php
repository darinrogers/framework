<?php

namespace Framework\Test;

class TestableController extends \Framework\Controller
{
	protected function someAction()
	{
		
	}
	
	public function validateCsrfToken($csrfToken)
	{
	    parent::validateCsrfToken($csrfToken);
	}
}