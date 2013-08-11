<?php

namespace Test;

class TestableModel extends \Framework\Models\Model
{
    public function setThis($value)
    {
        $this->setProperty('this', $value);
    }
    
    public function setThat($value)
    {
        $this->setProperty('that', $value);
    }
}