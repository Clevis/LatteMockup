<?php

namespace Clevis\TemplatePreview;

use Clevis\TemplatePreview\Mocks\FakerMock;
use Clevis\TemplatePreview\Mocks\Macros;
use Latte\CompileException;
use Latte\Engine;
use Latte\RuntimeException;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;


class Renderer
{

	/** @var string path */
	private $template;

	/** @var string path */
	private $layout;

	/** @var string path */
	private $tempDir;

	/** @var string[] */
	private $vars = ['basePath', 'user'];

	/** @var Macros */
	private $macros;

	public function __construct($template, $layout, $tempDir)
	{
		$this->template = $template;
		$this->layout = $layout;
		$this->tempDir = $tempDir;

		if (file_exists($this->getParamsCache()))
		{
			$raw = file_get_contents($this->getParamsCache());
			$this->vars = json_decode($raw, TRUE);
		}
	}

	private function getParamsCache()
	{
		return "$this->tempDir/params.ser";
	}

	public function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
	{
		if ($var = Strings::match($errstr, '~^Undefined variable: (.+)$~')[1])
		{
			$this->vars[] = $var;
			@mkdir($this->tempDir);
			$raw = json_encode($this->vars);
			file_put_contents($this->getParamsCache(), $raw);

			throw new IncompleteParametersException;
		}
		else if (Strings::match($errstr, '~could not be converted to int~'))
		{
			throw new \Exception("$errstr. This cannot be solved automatically: if you really need int in template, "
			. "implement custom data provider in 'Clevis\\TemplatePreview\\MockProvider'.");
		}
		throw new \Exception($errstr, $errno);
	}

	private function buildParams()
	{
		$params = [];
		foreach ($this->vars as $var)
		{
			$params[$var] = new FakerMock($var);
		}
		$params['template'] = (object) [];
		return $params;
	}

	/**
	 * @param Engine $latte
	 * @param string $template path
	 * @throws \Exception
	 * @throws \Latte\RuntimeException
	 * @return NULL|string html
	 */
	private function renderTrial($latte, $template)
	{
		set_error_handler([$this, 'handleError']);

		try {
			$html = $latte->renderToString($template, $this->buildParams());
			restore_error_handler();
			return $html;
		}
		catch (IncompleteParametersException $e)
		{
			restore_error_handler();
			return NULL;
		}
		catch (CompileException $e)
		{
			if (strpos($e->getMessage(), 'Multiple {extends} declarations are not allowed') === 0)
			{
				$this->macros->setLayout(NULL);
			}
			return NULL;
		}
		catch (RuntimeException $e)
		{
			$m = Strings::match($e->getMessage(), '~Cannot include undefined (parent )?block \'([^\']+)\'~');
			if (!$m)
			{
				throw $e;
			}
			$this->macros->addBlock($m[1]);
			restore_error_handler();
			return NULL;
		}
	}

	/**
	 * @return string html
	 */
	public function render()
	{
		$latte = new Engine();

		$hack = Access($latte, '$baseTemplateClass');
		$hack->set('Clevis\\TemplatePreview\\Mocks\\Template');

		$latte->setTempDirectory($this->tempDir);
		$compiler = $latte->getCompiler();

		$latte->getParser()->shortNoEscape = TRUE;

		$this->macros = Macros::install($compiler);
		$this->macros->setLayout($this->layout);

		$latte->addFilter('date', function($obj, $format = '%x') {
			$d = new DateTime($obj->date);
			return strftime($format, $d->format('U'));
		});

		$html = NULL;
		do
		{
			$html = $this->renderTrial($latte, $this->template);
			// remove cached file so missing blocks can be added
			$cached = $latte->getCacheFile($this->template);
			if (file_exists($cached))
			{
				unlink($cached);
			}
		} while (!$html);

		return $html;
	}

}
