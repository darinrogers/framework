<?php

namespace Framework\Test;

class TestableConfig extends \Framework\Config
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function setEnvironment($environment)
    {
        parent::setEnvironment($environment);
    }
}