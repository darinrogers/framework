<?php

require_once __DIR__ . '/../Framework/bootstrap.php';

class RouterTest extends PHPUnit_Framework_TestCase
{
	public function testGetsControllerName()
	{
		$r = new \Framework\Router('/some-controller/some-action');
		
		$this->assertEquals('SomeController', $r->getControllerName());
		$this->assertEquals('someAction', $r->getActionName());
	}
	
	public function testDefaultsToIndexAction()
	{
		$r = new \Framework\Router('/some-controller');
		
		$this->assertEquals('SomeController', $r->getControllerName());
		$this->assertEquals('index', $r->getActionName());
	}
	
	public function testDefaultsToIndexControllerAndAction()
	{
		$r = new \Framework\Router('/');
		
		$this->assertEquals('Index', $r->getControllerName());
		$this->assertEquals('index', $r->getActionName());
	}
	
	public function testStripsQuerystringWhenParsingControllerAndActionNames()
	{
		$r = new \Framework\Router('/login?username=darin&password=password');
		
		$this->assertEquals('Login', $r->getControllerName());
		$this->assertEquals('index', $r->getActionName());
	}
}