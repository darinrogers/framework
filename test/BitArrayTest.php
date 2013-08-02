<?php

require_once __DIR__ . '/test_bootstrap.php';

class BitArrayTest extends PHPUnit_Framework_TestCase
{
    public function testCanTurnABitOn()
    {
        $ba = new \Framework\BitArray(254);
        $ba->turnOnBitAt(1);
        
        $this->assertEquals(255, $ba->getValue());
    }
    
    public function testCanTurnABitOff()
    {
        $ba = new \Framework\BitArray(255);
        $ba->turnOffBitAt(1);
        
        $this->assertEquals(254, $ba->getValue());
    }
    
    public function testDeterminesWhenABitIsOn()
    {
        $ba = new \Framework\BitArray(255);
        
        $this->assertTrue($ba->isBitSetAt(1));
    }
    
    public function testDeterminesWhenABitIsOff()
    {
        $ba = new \Framework\BitArray(254);
        
        $this->assertFalse($ba->isBitSetAt(1));
    }
    
    public function testTogglesBitsOff()
    {
        $ba = new \Framework\BitArray(255);
        $ba->toggleBitAt(1);
        
        $this->assertEquals(254, $ba->getValue());
    }
    
    public function testTogglesBitsOn()
    {
        $ba = new \Framework\BitArray(254);
        $ba->toggleBitAt(1);
        
        $this->assertEquals(255, $ba->getValue());
    }
    
    public function testReturnsFalseWhenBitDoesntExist()
    {
        $ba = new \Framework\BitArray(1);
        
        $this->assertEquals(0, $ba->isBitSetAt('2'));
    }
}

?>