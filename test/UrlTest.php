<?php

namespace Markenwerk\UrlUtil;

/**
 * Class UrlTest
 *
 * @package Markenwerk\UrlUtil
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{

	public function testParser()
	{
		$url = new Url('https://john:secret@mydomain.com:8443/path/to/resource?arg1=123&arg2=test#fragment');

		$scheme = $url->getScheme();
		fwrite(STDOUT, 'Scheme "' . $scheme . '"' . PHP_EOL);

		$hostname = $url->getHostname();
		fwrite(STDOUT, 'Hostname "' . $hostname . '"' . PHP_EOL);

		$port = $url->getPort();
		fwrite(STDOUT, 'Port "' . (string)$port . '"' . PHP_EOL);

		$username = $url->getUsername();
		fwrite(STDOUT, 'Username "' . $username . '"' . PHP_EOL);

		$password = $url->getPassword();
		fwrite(STDOUT, 'Password "' . $password . '"' . PHP_EOL);

		$path = $url->getPath();
		fwrite(STDOUT, 'Path "' . $path . '"' . PHP_EOL);

		$queryParameters = $url->getQueryParameters();
		foreach ($queryParameters as $queryParameter) {
			fwrite(STDOUT, 'Query parameter "' . $queryParameter->getKey() . '" is "' . $queryParameter->getValue() . '"' . PHP_EOL);
		}

		$fragment = $url->getFragment();
		fwrite(STDOUT, 'Fragment "' . $fragment . '"' . PHP_EOL);

		$url
			->setScheme('http')
			->setHostname('yourdomain.com')
			->setPort(8080)
			->setUsername('doe')
			->setPassword('supersecret')
			->setPath('path/to/another/resource')
			->removeQueryParameterByKey('arg2')
			->addQueryParameter(new QueryParameter('arg1', '456'))
			->addQueryParameter(new QueryParameter('arg3', 'test'))
			->setFragment('target');

		fwrite(STDOUT, 'URL "' . $url->buildUrl() . '"' . PHP_EOL);

		$expected = 'http://doe:supersecret@yourdomain.com:8080/path/to/another/resource?arg1=456&arg3=test#target';
		$this->assertEquals($expected, $url->buildUrl());

	}

}
