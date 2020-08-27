<?php

namespace Tests\unit\JsonParser\Rules;

use JsonParser\Loader\FileLoader;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;
use JsonParser\Rules\ArrayRule;

class ArrayRuleTest extends TestCase
{
	public function testSuccess()
	{
		$rules = [
			'screens' => new ArrayRule('specifications.screen', ['screen', 'hdr', 'frequency']),
		];

		$config = (new Config($rules))
			->setLoader(new FileLoader(__DIR__ . '/../../../data/Orders.json'))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);

		$this->assertEquals([
			[
				'screens' => [['65"/3840x2160 Пикс', 'HDR10 Pro', '50 Гц']]
			], [
				'screens' => [['55"/3840x2160 Пикс', 'HDR10 Pro', '70 Гц']]
			]
		], $parser->parse());
	}

}
