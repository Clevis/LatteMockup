<?php

use Symfony\Component\Console\Application;


$loader = require file_exists(__DIR__ . '/vendor')
	? __DIR__ . '/vendor/autoload.php'
	: __DIR__ . '/../../autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/src');

$helpers = [
	'tempDir' => new \Clevis\TemplatePreview\TempDirHelper(__DIR__.  '/temp'),
];

$set = new \Symfony\Component\Console\Helper\HelperSet($helpers);

$application = new Application();
$application->setHelperSet($set);
$application->add(new \Clevis\TemplatePreview\RenderCommand());
$application->run();
