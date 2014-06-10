<?php

namespace Tests\Clevis\TemplatePreview;

use Clevis\TemplatePreview\FakerMock;
use Tester\Assert;


require __DIR__ . '/bootstrap.php';

$root = new FakerMock();
Access($root, '$faker')->get()->seed(2);

Assert::same('Simona Zajíčková', (string) $root->name);
Assert::same('Vydrala se mu mátlo otřesem; přesto se pokoušel vstát. Já tam nechci! Nechoďte tam! Tam.', (string) $root->foo);
