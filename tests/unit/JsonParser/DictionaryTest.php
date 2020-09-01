<?php

namespace Tests\unit\JsonParser;

use JsonParser\Exceptions\UnknownDictionaryException;
use JsonParser\Loader\FileLoader;
use JsonParser\Rules\FromDictionaryRule;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;

class DictionaryTest extends TestCase
{
	public function testBasePath()
	{
		$rules = [
			'call_id' => new FromDictionaryRule('unknown_type', 'other_countries_sms_call_id'),
		];

		$config = (new Config($rules))
			->setBasePath('countries')
			->setLoader(new FileLoader(__DIR__ . '/../../data/Countries.json'))
			->setIgnoreErrors(true);

		$parser = (new JsonParser($config))->addDictionary('unknown_type', new class {});

		$this->expectExceptionMessage('Неизвестный тип справочника');
		$this->expectException(UnknownDictionaryException::class);

		$parser->parse();
	}
}
