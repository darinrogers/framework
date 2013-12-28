<?php

namespace Framework;

class Random
{
	const UPPER_CASE_ALPHA_NUMERIC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	const ALL_CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ `~!@#$%^&*()_+-=[]{}|;\':",./<>?';
	
	public function getString($length, $characters = null)
	{
		if ($characters === null) {
			$characters = self::ALL_CHARACTERS;
		}
		
		$charactersLength = strlen($characters);
		$outputString = '';
		
		for ($i = 0; $i < $length; $i++) {
			$outputString .= $characters[mt_rand(0, $charactersLength - 1)];
		}
		
		return $outputString;
	}
}