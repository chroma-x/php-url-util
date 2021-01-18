<?php

namespace ChromaX\UrlUtil;

/**
 * Interface QueryParameterInterface
 *
 * @package ChromaX\UrlUtil
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
	 * @return string
	 */
	public function getEscapedValue();

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function setValue($value);

}
