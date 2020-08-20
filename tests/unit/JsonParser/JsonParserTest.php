<?php

namespace Tests\unit\JsonParser;

use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;
use JsonParser\Rules\DotPathRule;
use JsonParser\Rules\ArrayRule;

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

	public function testBasePath()
	{
		$rules = [
			'product_name' => new DotPathRule('name'),
			'product_cost' => new DotPathRule('cost'),
		];

		$config = (new Config($rules))
			->setBasePath('data.orders')
			->setIgnoreErrors(true);

		$path = __DIR__ . '/../../data/UserOrders.json';
		$parser = new JsonParser($path, $config);

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

	public function testArrayRule()
	{
		$this->assertTrue(true);
	}

	public function testDotPathRule()
	{
		$this->assertTrue(true);
	}

	public function testIgnoreErrors()
	{
		$this->assertTrue(true);
	}
}
