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
	
	public function testParsesAdminIndex()
	{
		$r = new \Framework\Router('/admin');
		
		$this->assertEquals('Admin\Index', $r->getControllerName());
		$this->assertEquals('index', $r->getActionName());
	}
	
	public function testParsesAdminSubsectionIndex()
	{
		$r = new \Framework\Router('/admin/stuff');
		
		$this->assertEquals('Admin\Stuff', $r->getControllerName());
		$this->assertEquals('index', $r->getActionName());
	}
	
	public function testParsesAdminSubsectionWithAction()
	{
		$r = new \Framework\Router('/admin/stuff/yeah');
		
		$this->assertEquals('Admin\Stuff', $r->getControllerName());
		$this->assertEquals('yeah', $r->getActionName());
	}
	
	public function testAddsColonDelimitedPartsToGET()
	{
		$r = new \Framework\Router('/beta-code/code:123/email:yeah%40no.com');
		
		$this->assertEquals('BetaCode', $r->getControllerName());
		$this->assertEquals('index', $r->getActionName());
		$this->assertEquals('123', $_GET['code']);
		$this->assertEquals('yeah@no.com', $_GET['email']);
	}
}