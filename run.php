<?php

use Symfony\Component\Console\Application;


$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/src');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);

$helpers = [
	'tempDir' => new \Clevis\TemplatePreview\TempDirHelper(__DIR__.  '/temp'),
];

$set = new \Symfony\Component\Console\Helper\HelperSet($helpers);

$application = new Application();
$application->setHelperSet($set);
$application->add(new \Clevis\TemplatePreview\RenderCommand());
$application->run();
