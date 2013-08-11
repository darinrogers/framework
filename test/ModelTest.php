<?php

require_once __DIR__ . '/test_bootstrap.php';
require_once __DIR__ . '/TestableModel.php';

class ModelTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return \Test\TestableModel
     */
    private function _getModel()
    {
        return new \Test\TestableModel(array('this' => 'that'));
    }
    
    public function testDeltaDataExcludesUnchangedOriginal()
    {
        $m = $this->_getModel();
        
        $this->assertEquals(array(), $m->getDeltaDataset());
    }
    
    public function testDeltaDataExcludesDataOverwrittenWithSame()
    {
        $m = $this->_getModel();
        $m->setThis('that');
        
        $this->assertEquals(array(), $m->getDeltaDataset());
    }
    
    public function testDeltaDataIncludesChangedOriginalData()
    {
        $m = $this->_getModel();
        $m->setThis('somethingelse');
        
        $this->assertEquals(array('this' => 'somethingelse'), $m->getDeltaDataset());
    }
    
    public function testDeltaIncludesDataThatsBothChangedAndNew()
    {
        $m = $this->_getModel();
        $m->setThat(1);
        $m->setThat(2);
        
        $this->assertEquals(array('that' => 2), $m->getDeltaDataset());
    }
}