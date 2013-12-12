<?php

namespace Framework;

class Random
{
	public function getString($length, $characters = null)
	{
		if ($characters === null) {
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ `~!@#$%^&*()_+-=[]{}|;\':",./<>?';
		}
		
		$charactersLength = strlen($characters);
		$outputString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$outputString .= $characters[mt_rand(0, $charactersLength - 1)];
		}
		
		return $outputString;
	}
}