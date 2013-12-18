<?php

namespace Framework;

class Validator
{
	public function isValidStringLength($string, $min, $max)
	{
		$length = strlen($string);
	
		if ($length < $min || $length > $max) {
			return false;
		}
	
		return true;
	}
	
	public function isValidNumberBetween($number, $min, $max)
	{
		if ($number < $min || $number > $max) {
			return false;
		}
		
		return true;
	}
}