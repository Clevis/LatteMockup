<?php

$loader = require file_exists(__DIR__ . '/vendor')
	? __DIR__ . '/vendor/autoload.php'
	: __DIR__ . '/../../autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/src');

$project = __DIR__ . '/../../../app';


if (!isset($_GET['template']))
{

	foreach (\Nette\Utils\Finder::findFiles('*.latte')->from($project) as $file)
	{
		echo '<a href="?template=' . urlencode($file) . '">' . $file . '</a><br>';
	}
}
else
{
	$template = $_GET['template'];
	$layout = NULL;
	if (strpos(basename($template), '@') !== 0)
	{
		foreach (\Nette\Utils\Finder::findFiles('@layout.latte')->from($project) as $layout => $info)
		{
			// layout set
			break;
		}
	}
	$renderer = new \Clevis\TemplatePreview\Renderer($template, $layout, __DIR__ . '/temp');
	echo $renderer->render();
}
