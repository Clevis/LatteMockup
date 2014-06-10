<?php

namespace Clevis\TemplatePreview;

use Faker\Provider\Base;


class MockProvider extends Base
{

	// TODO: create aliases as array

	public function title()
	{
		return $this->generator->realText(100);
	}

}
