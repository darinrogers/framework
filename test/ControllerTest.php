<?php

use Framework\Test\TestableController;
require_once __DIR__ . '/test_bootstrap.php';
require_once __DIR__ . '/TestableController.php';

class ControllerTest extends PHPUnit_Framework_TestCase
{
	public function testRunActionRunsTheAction()
	{
		$c = $this->getMock('\\Framework\\Test\\TestableController', array('someAction'));
		$c->expects($this->once())
			->method('someAction');
		
		$c->runAction('some');
	}
	
	public function testUsesDefaultLayoutAndView()
	{
		$c = new \Framework\Test\TestableController();
		$c->runAction('some');
		
		$this->assertEquals(
			'layout : some-viewx', 
			$c->getResponse()->__toString()
		);
	}
	
	/**
	 * @expectedException \Framework\SecurityException
	 */
	public function testThrowsExceptionWhenInvalidCsrfToken()
	{
	    $c = $this->getMock('\\Framework\Test\\TestableController', array('getCsrfToken'));
	    $c->expects($this->once())
	       ->method('getCsrfToken')
	       ->will(
	           $this->returnValue('bcd234')
	       );
	    
	    $c->validateCsrfToken('abc123');
	}
	
	public function testNoExceptionWhenValidCsrfToken()
	{
	    $c = $this->getMock('\\Framework\Test\\TestableController', array('getCsrfToken'));
	    $c->expects($this->once())
	       ->method('getCsrfToken')
	       ->will(
	           $this->returnValue('bcd234')
	       );
	    
	    $c->validateCsrfToken('bcd234');
	}
}