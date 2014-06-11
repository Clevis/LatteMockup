<?php

namespace Clevis\TemplatePreview\Mocks;

use Clevis\TemplatePreview\MockProvider;
use Faker\Factory;
use InvalidArgumentException;
use Latte\arguments;
use Latte\filter;
use Latte\Helpers;
use Latte\Object;


class Template extends \Latte\Template
{

	/**
	 * Allow undefined filters
	 * @param $name
	 * @param $args
	 * @return mixed|string
	 */
	public function __call($name, $args)
	{
		try
		{
			return parent::__call($name, $args);
		}
		catch (\LogicException $e)
		{
			return implode(' ', $args) . "|$name";
		}
	}

}
