<?php

namespace Clevis\TemplatePreview;

use Latte\Macros\MacroSet;
use Latte;


class MockMacros extends MacroSet
{

	public function __construct(Latte\Compiler $compiler)
	{
		parent::__construct($compiler);
		$this->addMacro('control', function() {});
		$this->addMacro('href', NULL, NULL, function($node) {
			return 'echo " href=\"#' . $node->args . '\"";';
		});
	}

}
