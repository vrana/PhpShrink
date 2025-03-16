<?php
include __DIR__ . "/../phpShrink.php";

function check($code, $expected) {
	$shrinked = str_replace("\n", " ", phpShrink("<?php\n$code"));
	if ("<?php $expected" != $shrinked) {
		$backtrace = debug_backtrace()[0];
		echo "$backtrace[file]:$backtrace[line]:" . substr($shrinked, 6) . "\n";
	}
}

set_error_handler(function ($errno) {
	return ($errno == E_USER_WARNING);
});

//! inefficiencies
check('echo "a"."b",\'c\'."d$a"."e";', 'echo "abcd$a"."e"');

// officially unsupported
check('$ab = 1; echo $GLOBALS["ab"];', '$a=1;echo$GLOBALS["ab"];');

check('$ab = 1; echo $ab;', '$a=1;echo$a;');
check('$ab = 1; $cd = 2;', '$a=1;$b=2;');
check('define("AB", 1);', 'define("AB",1);');
check('function f($ab, $cd = 1) { return $ab; }', 'function f($a,$b=1){return$a;}');
check('class C { var $ab = 1; }', 'class C{var$ab=1;}');
check('class C { public $ab = 1; }', 'class C{var$ab=1;}');
check('class C { protected $ab = 1; }', 'class C{protected$ab=1;}');
check('class C { private $ab = 1; }', 'class C{private$ab=1;}');
check('class C { private $ab = 1; }', 'class C{private$ab=1;}');
check('class C { private function f($ab) { return $ab; }}', 'class C{private function f($a){return$a;}}');
check('class C { public function f($ab) { return $ab; }}', 'class C{function f($a){return$a;}}');
check('class C { private static $ab; }', 'class C{private static$ab;}');
check('class C { public static $ab; }', 'class C{static$ab;}');
check('class C { const AB = 1; }', 'class C{const AB=1;}');
check('class C { private const AB = 1; }', 'class C{private const AB=1;}');
check('class C { public $ab; function f($cd) { return $cd . $this->ab; }}', 'class C{var$ab;function f($b){return$b.$this->ab;}}');
check('namespace NS { class C { public $ab = 1; } } new NS\C; $ab = 2;', 'namespace NS{class C{var$ab=1;}}new NS\C;$a=2;');
check('new \stdClass;', 'new \stdClass;');
check('if (true) { echo "a"; } else { echo "b"; }', 'if(true)echo"a";else echo"b";');
check('for ($a=0; $a < 1; $a++) { echo 1; }', 'for($a=0;$a<1;$a++)echo 1;');
check('for ($a=0; $a < 1; $a++) echo 1;', 'for($a=0;$a<1;$a++)echo 1;');
check('for ($a=0; $a < 1; $a++) {}', 'for($a=0;$a<1;$a++);');
check('{if (true) {} echo 1;}', '{if(true);echo 1;}');
check('echo $_GET["a"];', 'echo$_GET["a"];');
check('$ab = 1; echo $ab . "$ab";', '$a=1;echo$a."$a";');
check('$ab = 1; function f() { global $ab; return $ab; }', '$a=1;function f(){global$a;return$a;}');
check('echo 1; echo 3;', 'echo 1,3;');
check('echo 1; /**/ echo 2;', 'echo 1,2;');
check('echo 1; ?>2<?php echo 3;', "echo 1,'2',3;");
check('/** preserve */ $a; /** ignore */ /* also ignore */ // ignore too', '/** preserve */$a;');
check('$a = 1; ?><?php ?><?php $a = 2;', '$a=1;$a=2;');
check('$a = 1; ?>', '$a=1;');
