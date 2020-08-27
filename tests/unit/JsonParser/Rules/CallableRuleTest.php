<?php

namespace Tests\unit\JsonParser\Rules;

use JsonParser\Loader\FileLoader;
use JsonParser\Rules\CallableRule;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;

class CallableRuleTest extends TestCase
{
	public function testSuccess()
	{
		$rules = [
			'test' => new CallableRule(function ($i) { return "{$i['name']}_{$i['id']}"; })
		];

		$config = (new Config($rules))
			->setLoader(new FileLoader(__DIR__ . '/../../../data/Orders.json'))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);

		$this->assertEquals([
			[
				'test' => 'Телевизор LG 65UN73506LB_1'
			], [
				'test' => 'Телевизор Samsung 65UN73506LB_2'
			]
		], $parser->parse());
	}

}
