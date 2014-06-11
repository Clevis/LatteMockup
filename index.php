<?php

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require file_exists(__DIR__ . '/vendor')
	? __DIR__ . '/vendor/autoload.php'
	: __DIR__ . '/../../autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/src');

\Tracy\Debugger::enable(\Tracy\Debugger::DEBUG);

$project = __DIR__ . '/../../../app';

$appLoader = new \Nette\Loaders\RobotLoader();
$storage = new \Nette\Caching\Storages\FileStorage(__DIR__.  '/temp');
$appLoader->setCacheStorage($storage);
$appLoader->addDirectory($project);
$appLoader->register();


if (!isset($_GET['template']))
{
	$templates = [];
	foreach (\Nette\Utils\Finder::findFiles('*.latte')->from($project) as $file)
	{
		$short = substr($file, strlen($project));
		$short = ltrim($short, '/');
		$package = explode('/', $short)[0];
		$templates[$package][realpath($file)] = $short;
	}
	$latte = new \Latte\Engine();
	$latte->render(__DIR__ . '/src/templates/list.latte', [
		'templates' => $templates,
	]);
}
else
{
	$template = $_GET['template'];
	$layout = NULL;
	if (strpos(basename($template), '@') !== 0)
	{
		$in = dirname($template);
		do
		{
			foreach (\Nette\Utils\Finder::findFiles('@layout.latte')->from($in) as $layout => $info)
			{
				// layout set
				break 2;
			}
			$in = dirname($in);
		} while ($in !== '/');
	}
	$renderer = new \Clevis\TemplatePreview\Renderer($template, $layout, __DIR__ . '/temp');
	echo $renderer->render();
}
