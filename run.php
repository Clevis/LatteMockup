<?php

use Symfony\Component\Console\Application;


$loader = require __DIR__ . '/vendor/autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/src');

\Tracy\Debugger::enable(\Tracy\Debugger::DEVELOPMENT);

$application = new Application();
$application->add(new \Clevis\TemplatePreview\FooCommand());
$application->run();
