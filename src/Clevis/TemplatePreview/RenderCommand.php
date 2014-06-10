<?php

namespace Clevis\TemplatePreview;

use Latte\Engine;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class RenderCommand extends Command
{

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

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$appDir = $this->getHelper('appDir')->get();
		$template = $input->getArgument('template');
		$layout = $input->getArgument('layout');

		if (strpos($template, '/') !== 0)
		{
			$template = "$appDir/$template";
		}
		if ($layout && strpos($layout, '/') !== 0)
		{
			$layout = "$appDir/$layout";
		}

		$renderer = new Renderer(
			$template,
			$layout,
			$this->getHelper('tempDir')->get()
		);
		$output->writeln($renderer->render());
	}

}
