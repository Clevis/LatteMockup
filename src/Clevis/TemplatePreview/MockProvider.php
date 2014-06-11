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

	public function flashType()
	{
		return 'flash-alert';
	}

}
