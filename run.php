#!/usr/bin/env php
<?php
include __DIR__ . "/phpShrink.php";

array_shift($argv);
if (!$argv) {
	$argv = array("php://stdin");
}
$input = "";
foreach ($argv as $i => $filename) {
	$file = file_get_contents($filename);
	$tokens = token_get_all($file);
	$token = end($tokens);
	$input .= $file;
	if (isset($argv[$i+1]) && !(is_array($token) && in_array($token[0], array(T_CLOSE_TAG, T_INLINE_HTML)))) {
		$input .= "\n?>";
	}
}
echo phpShrink($input) . "\n";
