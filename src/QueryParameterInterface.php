<?php

namespace Url;

/**
 * Interface QueryParameterInterface
 *
 * @package Url
 */
interface QueryParameterInterface
{

	/**
	 * QueryParameter constructor.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function __construct($key, $value);

	/**
	 * @return string
	 */
	public function getKey();

	/**
	 * @param string $key
	 * @return $this
	 */
	public function setKey($key);

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function setValue($value);

}
