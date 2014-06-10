<?php

namespace Clevis\TemplatePreview;

use Latte\Macros\MacroSet;
use Latte;


class MockMacros extends MacroSet
{

	/** @var string */
	protected $layout;

	protected $templateId = NULL;

	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	public function initialize()
	{
		$compiler = $this->getCompiler();
		if ($this->layout)
		{
			// prevent layout from extending itself
			if ($this->templateId === NULL || $this->templateId === $compiler->getTemplateId())
			{
				$compiler->openMacro('extends', $this->layout);
				$this->templateId = $compiler->getTemplateId();
			}
		}
	}

	public function __construct(Latte\Compiler $compiler)
	{
		parent::__construct($compiler);
		$this->addMacro('control', function() {});
		$this->addMacro('href', NULL, NULL, function($node) {
			return 'echo " href=\"#' . $node->args . '\"";';
		});
	}

}
