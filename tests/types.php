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
check('function f(iterable $a);', 'function f($a);');
check('function f(?iterable $a);', 'function f($a);');
check('function f() : int {}', 'function f() {}');
check('function f() : ?int;', 'function f();');
check('return $a ? f($b) : false;', 'return $a ? f($b) : false;');
check('return $a ? f($b): false;', 'return $a ? f($b): false;');
check('function () use ($a) : int {};', 'function () use ($a) {};');
check('function f($a = array(array(1))) : int {}', 'function f($a = array(array(1))) {}');
check('function &f() : int {}', 'function &f() {}');

// strings, comments and inline HTML are not code
check('"var int $a";', '"var int $a";');
check("'protected \\\$translations';", "'protected \\\$translations';");
check("preg_match('~\\n\\tprotected \\\$t~s', \$s);", "preg_match('~\\n\\tprotected \\\$t~s', \$s);");
check('$a = "public int $b";', '$a = "public int $b";');
check('<<<\'X\'
private string $a;
X;', '<<<\'X\'
private string $a;
X;');
check('/* function f(): int */', '/* function f(): int */');
check('<?php ?>public int $a;', '<?php ?>public int $a;');
check("function f(int \$a = 'x', string \$b) {}", "function f(\$a = 'x', \$b) {}");
check("function f(\$a = 'x'): int {}", "function f(\$a = 'x') {}");

// not supported
check('var A|B $a;', 'var A|B $a;');
