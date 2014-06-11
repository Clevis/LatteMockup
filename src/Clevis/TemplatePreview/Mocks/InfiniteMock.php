<?php

namespace Clevis\TemplatePreview\Mocks;


/**
 * Always returns new static on any access or call.
 * Same accessor returns same instance.
 */
class InfiniteMock implements \ArrayAccess, \Iterator, \Countable
{

	public static $objectId = 1;

	public $id; // for testing
	public $count = 5;

	protected $names = [];

	private $key = 0;
	private $values = [];

	public function __construct($names = [])
	{
		$this->id = self::$objectId++;
		$this->names = is_array($names) ? $names : [$names];
	}

	protected function getRandom($key)
	{
		if (!isset($this->values[$key]))
		{
			$names = $this->names;
			$names[] = $key;
			$this->values[$key] = new static($names);
		}
		return $this->values[$key];
	}

	public function __call($name, $arguments)
	{
		return $this->getRandom($name);
	}

	public function __get($name)
	{
		return $this->getRandom($name);
	}

	/**
	 * @link http://php.net/manual/en/iterator.current.php
	 * @return mixed
	 */
	public function current()
	{
		return $this->getRandom($this->key);
	}

	/**
	 * @link http://php.net/manual/en/iterator.next.php
	 * @return void
	 */
	public function next()
	{
		$this->key++;
	}

	/**
	 * Return the key of the current element
	 *
	 * @link http://php.net/manual/en/iterator.key.php
	 * @return mixed
	 */
	public function key()
	{
		return $this->key;
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 * Returns true on success or false on failure.
	 */
	public function valid()
	{
		return $this->key < $this->count;
	}

	/**
	 * @link http://php.net/manual/en/iterator.rewind.php
	 * @return void
	 */
	public function rewind()
	{
		$this->key = 0;
	}

	/**
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return TRUE;
	}

	/**
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->getRandom($offset);
	}

	/**
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->values[$offset] = $value;
	}

	/**
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->values[$offset]);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Count elements of an object
	 *
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 */
	public function count()
	{
		return $this->count;
	}
}
