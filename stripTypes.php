<?php
require 'vendor/autoload.php';

use PhpParser\Node;
use PhpParser\Node\Stmt\Property;
use PhpParser\PrettyPrinter;

class PHP5TypeDeclarationRemover extends \PhpParser\NodeVisitorAbstract {
	function enterNode(Node $node) {
		if ($node instanceof Node\Param && $node->type) {
			if (!($node->type instanceof Node\Name) && $node->type != 'array') {
				$node->type = null;
			}
		} elseif ($node instanceof Node\FunctionLike && $node->returnType) {
			$node->returnType = null;
		} elseif ($node instanceof Property && $node->type) {
			$node->type = null;
		}
	}
}

function stripTypes($code) {
	$parser = (new \PhpParser\ParserFactory)->createForNewestSupportedVersion();
	$ast = $parser->parse($code);
	$traverser = new \PhpParser\NodeTraverser;
	$traverser->addVisitor(new PHP5TypeDeclarationRemover);
	$modifiedAst = $traverser->traverse($ast);
	$prettyPrinter = new PrettyPrinter\Standard;
	return $prettyPrinter->prettyPrintFile($modifiedAst);
}
