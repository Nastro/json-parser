<?php

namespace Tests\unit\JsonParser;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JsonParser\Config;
use JsonParser\Exceptions\UnavailableUrlException;
use JsonParser\JsonParser;
use JsonParser\Loader\FileLoader;
use JsonParser\Loader\ILoader;
use JsonParser\Loader\TextLoader;
use JsonParser\Loader\UrlLoader;
use JsonParser\Rules\DotPathRule;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
	private function getParser(ILoader $loader)
	{
		$config = (new Config(['test' => new DotPathRule('test')]))->setLoader($loader);
		return new JsonParser($config);
	}

	public function testFileLoader()
	{
		$parser = $this->getParser(new FileLoader(__DIR__ . '/../../data/File.json'));
		$this->assertEquals([['test' => 'foo']], $parser->parse());
	}

	public function testTextLoader()
	{
		$parser = $this->getParser(new TextLoader('[{"test": "foo","number": 5}]'));
		$this->assertEquals([['test' => 'foo']], $parser->parse());
	}

	public function testUrlLoader()
	{
		$mock = new MockHandler([new Response(200, [], '[{"test": "foo","number": 5}]')]);

		$handlerStack = HandlerStack::create($mock);
		$client = new Client(['handler' => $handlerStack]);
		$loader = new UrlLoader('http://test.url', $client);

		$parser = $this->getParser($loader);
		$this->assertEquals([['test' => 'foo']], $parser->parse());
	}

	public function testUrlLoaderException()
	{
		$mock = new MockHandler([new Response(500, [], '')]);

		$handlerStack = HandlerStack::create($mock);
		$client = new Client(['handler' => $handlerStack]);
		$loader = new UrlLoader('http://test.url', $client);

		$this->expectExceptionMessage('Адрес http://test.url недоступен');
		$this->expectException(UnavailableUrlException::class);

		$this->getParser($loader)->parse();
	}
}
