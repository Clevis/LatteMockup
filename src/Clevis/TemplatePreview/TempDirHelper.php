<?php


namespace Clevis\TemplatePreview;


use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;


class TempDirHelper implements HelperInterface
{

	private $dir;
	private $set;

	/**
	 * Returns the canonical name of this helper.
	 *
	 * @return string The canonical name
	 * @api
	 */
	public function getName()
	{
		return __CLASS__;
	}

	/**
	 * @param string $tempDir path
	 */
	public function __construct($tempDir)
	{
		$this->dir = $tempDir;
	}

	public function __toString()
	{
		return $this->dir;
	}

	public function get()
	{
		return $this->dir;
	}

	/**
	 * Sets the helper set associated with this helper.
	 *
	 * @param HelperSet $helperSet A HelperSet instance
	 * @api
	 */
	public function setHelperSet(HelperSet $helperSet = NULL)
	{
		$this->set = $helperSet;
	}

	/**
	 * Gets the helper set associated with this helper.
	 *
	 * @return HelperSet A HelperSet instance
	 * @api
	 */
	public function getHelperSet()
	{
		return $this->set;
	}

}
