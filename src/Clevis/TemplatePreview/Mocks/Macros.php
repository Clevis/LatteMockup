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

	/** @var string */
	protected $templateId = NULL;

	/** @var string */
	protected $currentLink = NULL;

	/** @var array */
	protected $blocks = [];

	/**
	 * @param Compiler $compiler
	 * @return \Clevis\TemplatePreview\Mocks\Macros
	 */
	public static function install(Compiler $compiler)
	{
		/** @var self $me */
		$me = new static($compiler);

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

		return $me;
	}

	public function setBlocks(array $blocks)
	{
		$this->blocks = $blocks;
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

	public function addBlock($block)
	{
		if (!isset($this->blocks[$block]))
		{
			$this->blocks[$block] = FALSE;
		}
	}

	public function initialize()
	{
		$compiler = $this->getCompiler();

		foreach ($this->blocks as $block => &$added)
		{
			if ($added)
			{
				continue;
			}
			$compiler->openMacro('define', $block);
			$compiler->closeMacro('define');
			$added = TRUE;
		}

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
