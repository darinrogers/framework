<?php

require_once __DIR__ . '/test_bootstrap.php';
require_once __DIR__ . '/TestableConfig.php';

class ConfigTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @param unknown $env
	 * @return \Framework\Test\TestableConfig
	 */
	private function _getConfig($env)
	{
	    $c = new \Framework\Test\TestableConfig();
	    $c->setEnvironment($env);
	    
	    return $c;
	}
    
    public function testParsesMainIniByDefault()
	{
		$c = $this->_getConfig('dev');
		
		$this->assertEquals('whatever', $c['some-variable']);
	}
	
	public function testInheritsSections()
	{
	    $c = $this->_getConfig('dev2');
	    
	    $this->assertEquals('wha?', $c['something-different']);
	    $this->assertEquals('whatever', $c['some-variable']);
	    $this->assertEquals('overridden', $c['some-other-variable']);
	}
}