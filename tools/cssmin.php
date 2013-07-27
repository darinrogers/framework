<?php

require __DIR__ . '/cssmin-v3.0.1.php';

$result = CssMin::minify(file_get_contents($argv[1]));
file_put_contents($argv[1], $result);