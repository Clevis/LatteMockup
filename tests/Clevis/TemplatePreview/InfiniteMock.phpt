<?php

namespace Tests\Clevis\TemplatePreview;

use Clevis\TemplatePreview\InfiniteMock;
use Tester\Assert;


require __DIR__ . '/bootstrap.php';

$root = new InfiniteMock();
Assert::true($root->foo instanceof InfiniteMock);
Assert::same(1, $root->id);
Assert::same(NULL, Access($root, '$name')->get());

Assert::same(2, $root->foo->id);
Assert::same('foo', Access($root->foo, '$name')->get());
Assert::same(3, $root->foo->test->id);
Assert::same(2, $root->foo->id);

Assert::true($root['bar'] instanceof InfiniteMock);
Assert::same('bar', Access($root['bar'], '$name')->get());
Assert::same(4, $root['bar']->id);

$id = 5;
$count = 0;
foreach ($root as $node)
{
	Assert::true($node instanceof InfiniteMock);
	Assert::same($id, $node->id);
	$id++;
	$count++;
}
Assert::same($count, $root->count());
Assert::same($count, count($root));
