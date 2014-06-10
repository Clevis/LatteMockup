<?php

$loader = require __DIR__ . '/../../../vendor/autoload.php';
$loader->add('Clevis\\TemplatePreview', __DIR__ . '/../../../src');
$loader->add('Tests\\Clevis\\TemplatePreview', __DIR__ . '/../../../');

require __DIR__ . '/../../../vendor/nette/tester/Tester/bootstrap.php';
