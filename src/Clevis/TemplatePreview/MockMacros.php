<?php

namespace Clevis\TemplatePreview;

use Latte\CompileException;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte;
use Latte\PhpWriter;


/**
 * ifset always returns TRUE
 * translator returns original value
 * href returns original value
 * control is ignored
 * input is ignored
 */
class MockMacros extends MacroSet
{

	/** @var string */
	protected $layout;

	protected $templateId = NULL;

	public function __construct(Latte\Compiler $compiler)
	{
		parent::__construct($compiler);
		$this->addMacro('control', function() {});
		$this->addMacro('href', NULL, NULL, function($node) {
			return 'echo " href=\"#' . $node->args . '\"";';
		});
		$this->addMacro('ifset', '{', '}');
		$this->addMacro('input', function() {});
		$this->addMacro('_', [$this, 'macroTranslate'], [$this, 'macroTranslate']);
	}

	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	/**
	 * {_$var |modifiers}
	 */
	public function macroTranslate(MacroNode $node, PhpWriter $writer)
	{
		if ($node->closing) {
			return $writer->write('echo %modify(ob_get_clean())');

		} elseif ($node->isEmpty = ($node->args !== '')) {
			return $writer->write('echo %modify(%node.args)');

		} else {
			return 'ob_start()';
		}
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

}
