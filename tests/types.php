#!/usr/bin/env php
<?php
include __DIR__ . "/../phpShrink.php";

function check($code, $expected) {
	$stripped = stripTypes($code);
	if ($stripped != $expected) {
		$backtrace = debug_backtrace();
		$backtrace = $backtrace[0];
		echo "$backtrace[file]:$backtrace[line]:$stripped\n";
	}
}

// bugs
check('"var int $a";', '"var $a";');

check('function f(int $a) {}', 'function f($a) {}');
check('function f(int $a);', 'function f($a);');
check('function f(array $a, string $b);', 'function f(array $a, $b);');
check('function f(array $a);', 'function f(array $a);');
check('function f(\stdClass $a);', 'function f(\stdClass $a);');
check('function f(Custom $a);', 'function f(Custom $a);');
check('function f(?Custom $a);', 'function f($a);');
check('function f($a);', 'function f($a);');
check('function f(int &$a);', 'function f(&$a);');
check('function f($a = array());', 'function f($a = array());');
check('function f(): array;', 'function f();');
check('function f(): int;', 'function f();');
check('function (): int {}', 'function () {}');
check('function (): ?int {}', 'function () {}');
check('public ?array $a;', 'public $a;');
check('public stdClass $a;', 'public $a;');
check('public \stdClass $a;', 'public $a;');
check('public static $a;', 'public static $a;');
check('public static array $a;', 'public static $a;');
check('static public $a;', 'static public $a;');

// not supported
check('var A|B $a;', 'var A|B $a;');
