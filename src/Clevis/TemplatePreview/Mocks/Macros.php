<?php

namespace Clevis\TemplatePreview\Mocks;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte;
use Latte\PhpWriter;


/**
 * ifset always returns TRUE
 * translator returns original value
 * href returns original value prepended with hash
 * link returns original value prepended with hash
 * plink returns original value prepended with hash
 * control is ignored
 * input is ignored
 * form is ignored
 * label is ignored
 * ifCurrent returns true for first link
 * name returns original value
 */
class Macros extends MacroSet
{

	/** @var string */
	protected $layout;

	protected $templateId = NULL;

	protected $currentLink = NULL;

	/**
	 * @param Compiler $compiler
	 * @param string $layout path
	 */
	public static function install(Compiler $compiler, $layout)
	{
		/** @var self $me */
		$me = new static($compiler);
		$me->setLayout($layout);

		$me->addMacro('control', function() {});
		$me->addMacro('href', NULL, NULL, function($node) {
			return 'echo " href=\"#' . $node->args . '\"";';
		});
		$me->addMacro('link', function($node) {
			return 'echo "#' . $node->args . '\"";';
		});
		$me->addMacro('plink', function($node) {
			return 'echo "#' . $node->args . '\"";';
		});
		$me->addMacro('ifset', 'if (TRUE) {', '}');
		$me->addMacro('input', function() {});
		$me->addMacro('form', '{', '}');
		$me->addMacro('label', '{', '}');
		$me->addMacro('name', NULL, NULL, function($node) {
			return 'echo "' . $node->args . '";';
		});

		$me->addMacro('ifCurrent', [$me, 'macroIfCurrent'], '}');
		$me->addMacro('_', [$me, 'macroTranslate'], [$me, 'macroTranslate']);
	}

	public function setLayout($layout)
	{
		$this->layout = $layout;
	}

	public function macroIfCurrent(MacroNode $node, PhpWriter $writer)
	{
		if (!$this->currentLink && $this->currentLink === $node->args)
		{
			$this->currentLink = $node->args;
			return 'if (TRUE) {';
		}
		else
		{
			return 'if (FALSE) {';
		}
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