<?php

namespace Tests\unit\JsonParser\Rules;

use JsonParser\Loader\FileLoader;
use JsonParser\Rules\DotPathRule;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;

class DotPathRuleTest extends TestCase
{
	public function testSuccess()
	{
		$rules = [
			'isSmartTv' => new DotPathRule('specifications.functional.smartTv')
		];

		$config = (new Config($rules))
			->setLoader(new FileLoader(__DIR__ . '/../../../data/Orders.json'))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);

		$this->assertEquals([
			[
				'isSmartTv' => true
			], [
				'isSmartTv' => false
			]
		], $parser->parse());
	}

}
