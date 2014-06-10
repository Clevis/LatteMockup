<?php

namespace Clevis\TemplatePreview;


use Latte\Engine;
use Nette\Utils\DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class FooCommand extends Command
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
//		$input->getArgument('layout');

		$latte = new Engine();
		new MockMacros($latte->getCompiler());
		$latte->addFilter('date', function($date, $format = '%x') {
			$d = new DateTime();
			return strftime($format, $d->format('U'));
		});

		$args = [
			'profile' => new FakerMock(),
			'userEntity' => new FakerMock(),
			'basePath' => '',
		];
		$html = $latte->renderToString($input->getArgument('template'), $args);

		$output->writeln($html);
	}
}
