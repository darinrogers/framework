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
}