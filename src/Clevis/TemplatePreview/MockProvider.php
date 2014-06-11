<?php

namespace Clevis\TemplatePreview;

use Faker\Generator;
use Faker\Provider;


/**
 * @property-read Generator|Provider\Image $generator
 */
class MockProvider extends Provider\Base
{

	public function title()
	{
		return $this->generator->realText(100);
	}

	public function imagePath()
	{
		return $this->generator->image();
	}

	public function basePath()
	{
		return '';
	}

	public function flashesType()
	{
		return 'flash-alert';
	}

	/**
	 * presumably DateTime->format()
	 * _ to prevent calling generator->format
	 * @param null $format
	 */
	public function _format($format = NULL)
	{
		return $this->generator->dateTime()->format($format);
	}

}
