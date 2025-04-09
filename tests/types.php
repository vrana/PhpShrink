#!/usr/bin/env php
<?php
include __DIR__ . "/../stripTypes.php";

function check($code, $expected, $stack = 0) {
	try {
		$stripped = substr(stripTypes("<?php\n$code"), strlen("<?php\n\n"));
	} catch (\PhpParser\Error $e) {
		$stripped = $e->getMessage();
	}
	$stripped = preg_replace('~\\s+~', ' ', $stripped);
	if ($stripped != $expected) {
		$backtrace = debug_backtrace();
		$backtrace = $backtrace[$stack];
		echo "$backtrace[file]:$backtrace[line]:$stripped\n";
	}
}

function checkClass($code, $expected) {
	check("class C { $code }", "class C { $expected }", 1);
}

check('"var int $a";', '"var int {$a}";');
check('function f(int $a) { }', 'function f($a) { }');
check('function f(array $a, string $b) { }', 'function f(array $a, $b) { }');
check('function f(array $a) { }', 'function f(array $a) { }');
check('function f(\stdClass $a) { }', 'function f(\stdClass $a) { }');
check('function f(Custom $a) { }', 'function f(Custom $a) { }');
check('function f(?Custom $a) { }', 'function f($a) { }');
check('function f($a) { }', 'function f($a) { }');
check('function f(int &$a) { }', 'function f(&$a) { }');
check('function f($a = array()) { }', 'function f($a = array()) { }');
check('function f(): array { }', 'function f() { }');
check('function f(): int { }', 'function f() { }');
check('function (): int { };', 'function () { };');
check('function (): ?int { };', 'function () { };');
checkClass('abstract function f(int $a);', 'abstract function f($a);');
checkClass('public ?array $a;', 'public $a;');
checkClass('public stdClass $a;', 'public $a;');
checkClass('public \stdClass $a;', 'public $a;');
checkClass('public static $a;', 'public static $a;');
checkClass('public static array $a;', 'public static $a;');
checkClass('static public $a;', 'public static $a;');
checkClass('var A|B $a;', 'var $a;');
