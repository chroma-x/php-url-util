<?php

namespace Url;

/**
 * Class Url
 *
 * @package Url
 */
class Url
{

	const URL_SCHEME_SEPARATOR = '://';
	const URL_CREDENTIALS_SEPARATOR = ':';
	const URL_AUTHENTICATION_SEPARATOR = '@';
	const URL_PORT_SEPARATOR = ':';
	const URL_QUERYSTRING_SEPARATOR = '?';
	const URL_QUERY_SEPARATOR = '&';
	const URL_FRAGMENT_SEPARATOR = '#';

	/**
	 * @var string
	 */
	private $scheme;

	/**
	 * @var string
	 */
	private $hostname;

	/**
	 * @var int
	 */
	private $port;

	/**
	 * @var string
	 */
	private $path;

	/**
	 * @var QueryParameterInterface[]
	 */
	private $queryParameters = array();

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $fragment;

	/**
	 * Url constructor.
	 *
	 * @param string $url
	 */
	public function __construct($url = null)
	{
		$this->parseUrl($url);
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function parseUrl($url)
	{
		if (!is_string($url)) {
			$argumentType = (is_object($url)) ? get_class($url) : gettype($url);
			throw new \InvalidArgumentException('Expected URL as string; got ' . $argumentType);
		}
		$scheme = parse_url($url, PHP_URL_SCHEME);
		if (!is_null($scheme)) {
			$this->setScheme($scheme);
		}
		$hostname = parse_url($url, PHP_URL_HOST);
		if (!is_null($hostname)) {
			$this->setHostname($hostname);
		}
		$port = parse_url($url, PHP_URL_PORT);
		if (!is_null($port)) {
			$this->setPort($port);
		}
		$username = parse_url($url, PHP_URL_USER);
		if (!is_null($username)) {
			$this->setUsername($username);
		}
		$password = parse_url($url, PHP_URL_PASS);
		if (!is_null($password)) {
			$this->setPassword($password);
		}
		$path = parse_url($url, PHP_URL_PATH);
		if (!is_null($path)) {
			$this->setPath($path);
		}
		$queryString = parse_url($url, PHP_URL_QUERY);
		if (!is_null($queryString)) {
			$this->parseQueryString($queryString);
		}
		$fragment = parse_url($url, PHP_URL_FRAGMENT);
		if (!is_null($fragment)) {
			$this->setFragment($fragment);
		}
		return $this;
	}

	/**
	 * @param string $queryString
	 * @return $this
	 */
	public function parseQueryString($queryString)
	{
		$queryParameters = array();
		parse_str($queryString, $queryParameters);
		foreach ($queryParameters as $queryParameterKey => $queryParameterValue) {
			$this->addQueryParameter(new QueryParameter($queryParameterKey, $queryParameterValue));
		}
		return $this;
	}

	/**
	 * @return string
	 */
	public function buildUrl()
	{
		$url = $this->getScheme() . self::URL_SCHEME_SEPARATOR;
		$hostname = $this->getHostname();
		if ($this->hasUsername() || $this->hasPassword()) {
			$password = $this->hasPassword() ? self::URL_CREDENTIALS_SEPARATOR . $this->getPassword() : '';
			$hostname = $this->getUsername() . $password . self::URL_AUTHENTICATION_SEPARATOR . $hostname;
		}
		$url .= $hostname;
		if ($this->hasPort()) {
			$url .= self::URL_PORT_SEPARATOR . (string)$this->getPort();
		}
		$url .= $this->getPath();
		if ($this->hasQueryParameters()) {
			$url .= self::URL_QUERYSTRING_SEPARATOR . $this->buildQueryString();
		}
		if ($this->hasFragment()) {
			$url .= self::URL_FRAGMENT_SEPARATOR . $this->getFragment();
		}
		return $url;
	}

	/**
	 * @return string
	 */
	public function buildQueryString()
	{
		if (!$this->hasQueryParameters()) {
			return null;
		}
		$queryParameters = array();
		foreach ($this->getQueryParameters() as $queryParameter) {
			$queryParameters[] = $queryParameter->getKey() . '=' . $queryParameter->getValue();
		}
		return implode(self::URL_QUERY_SEPARATOR, $queryParameters);
	}

	/**
	 * @return string
	 */
	public function getScheme()
	{
		return $this->scheme;
	}

	/**
	 * @return bool
	 */
	public function hasScheme()
	{
		return !is_null($this->scheme);
	}

	/**
	 * @param string $scheme
	 * @return $this
	 */
	public function setScheme($scheme)
	{
		if (!is_string($scheme)) {
			$argumentType = (is_object($scheme)) ? get_class($scheme) : gettype($scheme);
			throw new \InvalidArgumentException('Expected scheme as string; got ' . $argumentType);
		}
		$this->scheme = $scheme;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getHostname()
	{
		return $this->hostname;
	}

	/**
	 * @return bool
	 */
	public function hasHostname()
	{
		return !is_null($this->hostname);
	}

	/**
	 * @param string $hostname
	 * @return $this
	 */
	public function setHostname($hostname)
	{
		if (!is_string($hostname)) {
			$argumentType = (is_object($hostname)) ? get_class($hostname) : gettype($hostname);
			throw new \InvalidArgumentException('Expected hostname as string; got ' . $argumentType);
		}
		$this->hostname = $hostname;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @return bool
	 */
	public function hasPort()
	{
		return !is_null($this->port);
	}

	/**
	 * @param int $port
	 * @return $this
	 */
	public function setPort($port)
	{
		if (!is_int($port)) {
			$argumentType = (is_object($port)) ? get_class($port) : gettype($port);
			throw new \InvalidArgumentException('Expected port as integer; got ' . $argumentType);
		}
		$this->port = $port;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->path;
	}

	/**
	 * @return bool
	 */
	public function hasPath()
	{
		return !is_null($this->path);
	}

	/**
	 * @param string $path
	 * @return $this
	 */
	public function setPath($path)
	{
		if (!is_string($path)) {
			$argumentType = (is_object($path)) ? get_class($path) : gettype($path);
			throw new \InvalidArgumentException('Expected path as string; got ' . $argumentType);
		}
		$this->path = '/' . ltrim($path, '/');
		return $this;
	}

	/**
	 * @return QueryParameterInterface[]
	 */
	public function getQueryParameters()
	{
		return $this->queryParameters;
	}

	/**
	 * @return bool
	 */
	public function hasQueryParameters()
	{
		return count($this->queryParameters) > 0;
	}

	/**
	 * @return int
	 */
	public function countQueryParameters()
	{
		return count($this->queryParameters);
	}

	/**
	 * @param QueryParameterInterface[] $queryParameters
	 * @return $this
	 */
	public function setQueryParameters($queryParameters)
	{
		if (!is_array($queryParameters)) {
			$argumentType = (is_object($queryParameters)) ? get_class($queryParameters) : gettype($queryParameters);
			throw new \InvalidArgumentException('Expected query parameters as array; got ' . $argumentType);
		}
		foreach ($queryParameters as $queryParameter) {
			if (!$queryParameter instanceof QueryParameterInterface) {
				$argumentType = (is_object($queryParameter)) ? get_class($queryParameter) : gettype($queryParameter);
				throw new \InvalidArgumentException('Expected query parameters as array of QueryParameterInterface implementations; found ' . $argumentType);
			}
		}
		$this->queryParameters = $queryParameters;
		return $this;
	}

	/**
	 * @param QueryParameterInterface $queryParameter
	 * @return $this
	 */
	public function addQueryParameter(QueryParameterInterface $queryParameter)
	{
		for ($i = 0; $i < count($this->queryParameters); $i++) {
			if ($this->queryParameters[$i]->getKey() === $queryParameter->getKey()) {
				$this->queryParameters[$i] = $queryParameter;
				return $this;
			}
		}
		$this->queryParameters[] = $queryParameter;
		return $this;
	}

	/**
	 * @param QueryParameterInterface $queryParameter
	 * @return $this
	 */
	public function removeQueryParameter(QueryParameterInterface $queryParameter)
	{
		for ($i = 0; $i < count($this->queryParameters); $i++) {
			if ($this->queryParameters[$i]->getKey() === $queryParameter->getKey()) {
				unset($this->queryParameters[$i]);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function removeQueryParameterByKey($key)
	{
		for ($i = 0; $i < count($this->queryParameters); $i++) {
			if ($this->queryParameters[$i]->getKey() === $key) {
				unset($this->queryParameters[$i]);
				return $this;
			}
		}
		return $this;
	}

	/**
	 * @return $this
	 */
	public function clearQueryParameters()
	{
		$this->queryParameters = array();
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @return bool
	 */
	public function hasUsername()
	{
		return !is_null($this->username);
	}

	/**
	 * @param string $username
	 * @return $this
	 */
	public function setUsername($username)
	{
		if (!is_string($username)) {
			$argumentType = (is_object($username)) ? get_class($username) : gettype($username);
			throw new \InvalidArgumentException('Expected username as string; got ' . $argumentType);
		}
		$this->username = $username;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return bool
	 */
	public function hasPassword()
	{
		return !is_null($this->password);
	}

	/**
	 * @param string $password
	 * @return $this
	 */
	public function setPassword($password)
	{
		if (!is_string($password)) {
			$argumentType = (is_object($password)) ? get_class($password) : gettype($password);
			throw new \InvalidArgumentException('Expected password as string; got ' . $argumentType);
		}
		$this->password = $password;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFragment()
	{
		return $this->fragment;
	}

	/**
	 * @return bool
	 */
	public function hasFragment()
	{
		return !is_null($this->fragment);
	}

	/**
	 * @param string $fragment
	 * @return $this
	 */
	public function setFragment($fragment)
	{
		if (!is_string($fragment)) {
			$argumentType = (is_object($fragment)) ? get_class($fragment) : gettype($fragment);
			throw new \InvalidArgumentException('Expected fragment as string; got ' . $argumentType);
		}
		$this->fragment = $fragment;
		return $this;
	}

}
