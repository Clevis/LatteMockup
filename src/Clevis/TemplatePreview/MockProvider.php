<?php

namespace Clevis\TemplatePreview;

use Faker\Provider\Base;


class MockProvider extends Base
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

}
