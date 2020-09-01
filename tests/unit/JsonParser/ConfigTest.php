<?php

namespace Tests\unit\JsonParser;

use JsonParser\Exceptions\UnknownDictionaryException;
use JsonParser\Loader\FileLoader;
use JsonParser\Loader\TextLoader;
use JsonParser\Rules\DotPathRule;
use JsonParser\Rules\FromDictionaryRule;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;

class ConfigTest extends TestCase
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

	public function testSetRules()
	{
		$rules = [
			'product_name' => new DotPathRule('name'),
			'product_cost' => new DotPathRule('cost'),
		];

		$config = (new Config([]))
			->setRules($rules)
			->setBasePath('data.orders')
			->setLoader(new TextLoader($this->data))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);

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
}
