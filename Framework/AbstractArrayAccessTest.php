<?php

namespace Framework;

abstract class AbstractArrayAccessTest extends \PHPUnit_Framework_TestCase
{
    abstract protected function getTestedClasses();
    
    public function provider()
    {
        $objects = array();
        $dataset = array(
            'id' => '', 
            'data' => array('this' => 'that')
        );
        
        foreach ($this->getTestedClasses() as $testedClass) {
            $objects[] = array(new $testedClass($dataset));
        }
        
        return $objects;
    }
    
    /**
     * @dataProvider provider
     */
    public function testTellsWhenAnOffsetExists($object)
    {
        $this->assertTrue(isset($object['this']));
    }
    
    /**
     * @dataProvider provider
     */
    public function testTesllWhenAnOffsetDoesntExist($object)
    {
        $this->assertFalse(isset($object['that']));
    }
    
    /**
     * @dataProvider provider
     */
    public function testGetsAnOffset($object)
    {
        $this->assertEquals('that', $object['this']);
    }
    
    /**
     * @dataProvider provider
     */
    public function testGetsNullWhenAnOffsetDoesntExist($object)
    {
        $this->assertNull($object['that']);
    }
    
    /**
     * @dataProvider provider
     */
    public function testSetsAnAssociativeOffset($object)
    {
        $object['blah'] = 'something';
        
        $this->assertEquals('something', $object['blah']);
    }
    
    /**
     * @dataProvider provider
     */
    public function testUnsetsAnOffset($object)
    {
        unset($object['this']);
        
        $this->assertNull($object['this']);
    }
}