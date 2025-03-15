#!/usr/bin/env php
<?php
include __DIR__ . "/phpShrink.php";

array_shift($argv);
if (!$argv) {
	$argv = array("php://stdin");
}
foreach ($argv as $filename) {
	echo phpShrink(file_get_contents($filename)) . "\n";
}
