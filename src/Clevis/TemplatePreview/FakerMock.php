<?php

namespace Clevis\TemplatePreview;

use Faker\Factory;
use Faker\Provider\File;
use InvalidArgumentException;
use Nette\Utils\Strings;


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
		foreach (array_reverse($this->names) as $name)
		{
			try
			{
				return $f->{(string)$name}();
			}
			catch (InvalidArgumentException $e)
			{
				// provider not found
			}
		}
		return $f->realText(100);
	}

}
