<?php

namespace Clevis\TemplatePreview;

use Faker\Factory;
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

	public function __construct($name = NULL)
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
		$name = Strings::webalize($this->name);
		$f = static::$faker;
		try
		{
			return $f->$name();
		}
		catch (InvalidArgumentException $e)
		{
			return $f->realText(100);
		}
	}

}
