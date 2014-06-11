<?php

namespace Clevis\TemplatePreview\Mocks;

use Clevis\TemplatePreview\MockProvider;
use Faker\Factory;
use InvalidArgumentException;


/**
 * Always returns new static on any access or call.
 * Same accessor returns same instance.
 * Has automatic return type recognition.
 */
class FakerMock extends InfiniteMock
{

	/**
	 * @var \Faker\Generator
	 */
	static $faker;

	public function __construct($name = [])
	{
		parent::__construct($name);
		if (!static::$faker)
		{
			static::$faker = Factory::create('cs_CZ');
			static::$faker->addProvider(new MockProvider(static::$faker));
		}
	}

	public function __toString()
	{
		$f = static::$faker;
		$names = array_filter($this->names, function($name) {
			// ignore indexes from Iterator
			return $name && !is_int($name);
		});

		// for: foo->flash->type
		// try: fooFlashType, flashType, type
		// try: fooFlash, flash
		// try: foo
		do
		{
			$stack = $names;
			foreach ($stack as &$name)
			{
				$name = ucFirst($name);
			}
			do
			{
				try
				{
					$method = lcFirst(implode('', $stack));

					// method on generator that does not invoke provider
					if ($method === 'format')
					{
						$method = '_format';
					}

					return $f->$method();
				}
				catch (InvalidArgumentException $e)
				{
					// provider not found
				}
			} while (array_shift($stack) && $stack);

		} while (array_pop($names) && $names);

		return $f->realText(100);
	}

}
