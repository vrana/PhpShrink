#!/usr/bin/env php
<?php
include __DIR__ . "/../phpShrink.php";

$failed = 0;

function check($code, $expected) {
	global $failed;
	$converted = shortArrays("<?php\n$code");
	if ($converted != "<?php\n$expected") {
		$backtrace = debug_backtrace();
		$backtrace = $backtrace[0];
		echo "$backtrace[file]:$backtrace[line]:" . substr($converted, 6) . "\n";
		$failed++;
	}
}

check('array();', '[];');
check('array(1, 2);', '[1, 2];');
check('array("a" => array(1), "b" => f(2));', '["a" => [1], "b" => f(2)];');
check('f(array(g(1)), (2));', 'f([g(1)], (2));');
check('array(array(array(1), 2));', '[[[1], 2]];');
check('array(f(array()));', '[f([])];');
check('array (1);', '[ 1];');
check('array/* c */(1);', '[/* c */1];');
check('function f(array $a = array()) {}', 'function f(array $a = []) {}');
check('function f(): array { return array(); }', 'function f(): array { return []; }');
check('(array) $a;', '(array) $a;');
check('"array(1)"; \'array(1)\'; // array(1)', '"array(1)"; \'array(1)\'; // array(1)');
check('"$a[0] array(1)";', '"$a[0] array(1)";');
check('<<<X
array(1)
X;', '<<<X
array(1)
X;');
check('?>array(1)<?php array(1);', '?>array(1)<?php [1];');
check('list($a, $b) = array(1, 2);', 'list($a, $b) = [1, 2];');
check('$a = array(function ($b) use ($c) { return array($b); });', '$a = [function ($b) use ($c) { return [$b]; }];');

exit($failed ? 1 : 0);
