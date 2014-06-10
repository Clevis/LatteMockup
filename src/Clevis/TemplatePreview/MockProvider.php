<?php

namespace Clevis\TemplatePreview;

use Faker\Provider\Base;


class MockProvider extends Base
{

	public function title()
	{
		return $this->generator->realText(100);
	}

}
