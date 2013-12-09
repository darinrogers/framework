#! /usr/local/bin/php
<?php

$output = array();
$returnValue;
exec('phpunit /var/www/html/framework2/test', $output, $returnValue);

if ($returnValue !== 0) {
	
	echo implode("\n", $output);
	exit(1);
}

exit(0);