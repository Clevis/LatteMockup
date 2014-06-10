<?php

namespace Clevis\TemplatePreview;


use Latte\Engine;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class FooCommand extends Command
{

	private $output;
	private $vars = ['basePath', 'user'];

	protected function configure()
	{
		$this
			->setName('render')
			->setDescription('Render latte to html')
			->addArgument(
				'template',
				InputArgument::REQUIRED,
				'Path to template file'
			)
			->addArgument(
				'layout',
				InputArgument::OPTIONAL,
				'Path to layout file'
			)
		;
	}

	public function handleError($errno, $errstr, $errfile, $errline, array $errcontext)
	{
		if ($errno === 8)
		{
			$var = Strings::match($errstr, '~^Undefined variable: (.+)$~')[1];
			$this->vars[] = $var;
		}
		throw new IncompleteParametersException;
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

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->output = $output;
//		$input->getArgument('layout');

		$latte = new Engine();
		new MockMacros($latte->getCompiler());
		$latte->addFilter('date', function($date, $format = '%x') {
			$d = new DateTime();
			return strftime($format, $d->format('U'));
		});

		$html = NULL;
		do
		{
			$html = $this->renderTrial($latte, $input->getArgument('template'));
		} while (!$html);

		$output->writeln($html);
	}
}
