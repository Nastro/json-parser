<?php

namespace Tests\unit\JsonParser;

use JsonParser\Dictionary;
use JsonParser\Exceptions\InvalidJsonFormatException;
use JsonParser\Exceptions\InvalidPathNodeException;
use JsonParser\Loader\TextLoader;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;
use JsonParser\Rules\DotPathRule;

class JsonParserTest extends TestCase
{
	private $data = <<<JSON
		{
			"data": {
				"user": {
					"name":"Mr. Test",
					"account":"6512431203",
					"email":"test@test.test"
				},
				"orders": [
					{
						"id":1,
						"name":"Cookie",
						"cost":12.5
					},{
						"id":2,
						"name":"Milk",
						"cost":17
					},{
						"id":3,
						"name":"Apple",
						"cost":19
					}
				]
			}
		}
JSON;

	private function getParser()
	{
		$rules = [
			'product_name' => new DotPathRule('name'),
			'product_cost' => new DotPathRule('cost'),
		];

		$config = (new Config($rules))
			->setBasePath('data.orders')
			->setLoader(new TextLoader($this->data))
			->setIgnoreErrors(true);

		return new JsonParser($config);
	}

	public function testBasePath()
	{
		$parser = $this->getParser();

		$this->assertEquals([
			[
				'product_name' => 'Cookie',
				'product_cost' => 12.5
			], [
				'product_name' => 'Milk',
				'product_cost' => 17
			], [
				'product_name' => 'Apple',
				'product_cost' => 19
			]
		], $parser->parse());
	}

	public function testInvalidBasePath()
	{
		$rules = [
			'product_name' => new DotPathRule('name'),
			'product_cost' => new DotPathRule('cost'),
		];

		$config = (new Config($rules))
			->setBasePath('not.found')
			->setLoader(new TextLoader($this->data))
			->setIgnoreErrors(true);

		$this->expectExceptionMessage('Нода с базовым путем not.found не существует');
		$this->expectException(InvalidPathNodeException::class);

		$parser = new JsonParser($config);
		$parser->parse();
	}

	public function testDictionaries()
	{
		$parser = $this->getParser();

		$parser->addDictionary('foo', ['a' => 'b', 'c' => 'd']);
		$parser->addDictionary('bar', ['e' => 'd', 'f' => 'g']);

		$dictionaries = $parser->getDictionaries();
		$dictionary = $parser->getDictionary('foo');

		$this->assertCount(2, $dictionaries);
		$this->assertInstanceOf(Dictionary::class, $dictionary);
		$this->assertEquals(['a' => 'b', 'c' => 'd'], $dictionary->getList());
	}

	public function testSetJson()
	{
		$rules = [
			'product_name' => new DotPathRule('name'),
			'product_cost' => new DotPathRule('cost'),
		];

		$config = (new Config($rules))
			->setBasePath('data.orders')
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);

		$parser->setJson($this->data);

		$this->assertEquals(json_decode($this->data, true), $parser->getOriginalJson());
		$this->assertEquals([
			[
				"id" => 1,
				"name" => "Cookie",
				"cost" => 12.5
			],[
				"id" => 2,
				"name" => "Milk",
				"cost" => 17
			],[
				"id" => 3,
				"name" => "Apple",
				"cost" => 19
			]
		], $parser->getJson());
	}

	public function testInvalidJson()
	{
		$rules = [
			'product_name' => new DotPathRule('name'),
			'product_cost' => new DotPathRule('cost'),
		];

		$config = (new Config($rules))
			->setBasePath('data.orders')
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);

		$this->expectExceptionMessage('Проблема с форматом json');
		$this->expectException(InvalidJsonFormatException::class);

		$parser->setJson('{"a": bbb}');
	}
}
