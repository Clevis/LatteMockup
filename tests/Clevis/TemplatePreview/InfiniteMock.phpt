<?php

namespace Tests\Clevis\TemplatePreview;

use Clevis\TemplatePreview\InfiniteMock;
use Tester\Assert;


require __DIR__ . '/bootstrap.php';

$root = new InfiniteMock();
Assert::true($root->foo instanceof InfiniteMock);
Assert::same(1, $root->id);
Assert::same([], Access($root, '$names')->get());

Assert::same(2, $root->foo->id);
Assert::same(['foo'], Access($root->foo, '$names')->get());
Assert::same(3, $root->foo->test->id);
Assert::same(2, $root->foo->id);

Assert::true($root['bar'] instanceof InfiniteMock);
Assert::same(['bar'], Access($root['bar'], '$names')->get());
Assert::same(4, $root['bar']->id);

Assert::same(['a', 'b', 'c'], Access($root->a->b->c, '$names')->get());

$id = 8;
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
