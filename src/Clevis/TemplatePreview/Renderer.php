<?php

namespace Clevis\TemplatePreview;

use Latte\Engine;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;


class Renderer
{

	private $template;
	private $layout;
	private $tempDir;
	private $vars = ['basePath', 'user'];

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
		if ($errno === 8)
		{
			$var = Strings::match($errstr, '~^Undefined variable: (.+)$~')[1];
			$this->vars[] = $var;

			@mkdir($this->tempDir);
			$raw = json_encode($this->vars);
			file_put_contents($this->getParamsCache(), $raw);

			throw new IncompleteParametersException;
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
		return $params;
	}

	/**
	 * @param Engine $latte
	 * @param string $template path
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
	}

	/**
	 * @return string html
	 */
	public function render()
	{
		$latte = new Engine();
		$latte->setTempDirectory($this->tempDir);
		$compiler = $latte->getCompiler();

		MockedBlockMacros::install($compiler);
		$mockMacros = new MockMacros($compiler);
		$mockMacros->setLayout($this->layout);
		dump($latte->getCompiler());

		$latte->addFilter('date', function($obj, $format = '%x') {
			$d = new DateTime($obj->date);
			return strftime($format, $d->format('U'));
		});

		$html = NULL;
		do
		{
			$html = $this->renderTrial($latte, $this->template);
		} while (!$html);

		return $html;
	}

}
