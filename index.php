<?php

$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/src');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);

$project = '/Users/mikulas/Dropbox/Projects2/khanovaskola/app';


if (!isset($_GET['template']))
{

	foreach (\Nette\Utils\Finder::findFiles('*.latte')->from($project) as $file)
	{
		echo '<a href="?template=' . urlencode($file) . '">' . $file . '</a><br>';
	}
}
else
{
	$renderer = new \Clevis\TemplatePreview\Renderer($_GET['template'], NULL, __DIR__ . '/temp');
	echo $renderer->render();
}
