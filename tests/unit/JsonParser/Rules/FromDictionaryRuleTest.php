<?php

namespace Tests\unit\JsonParser\Rules;

use JsonParser\Loader\FileLoader;
use JsonParser\Rules\FromDictionaryRule;
use PHPUnit\Framework\TestCase;
use JsonParser\JsonParser;
use JsonParser\Config;

class FromDictionaryRuleTest extends TestCase
{
	public function testArrayDictionary()
	{
		$rules = [
			'category' => new FromDictionaryRule('dictionaryName', 'category')
		];

		$config = (new Config($rules))
			->setLoader(new FileLoader(__DIR__ . '/../../../data/Orders.json'))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);
		$parser->addDictionary('dictionaryName', [
			1 => 'Тестовая категория 1',
			2 => 'Тестовая категория 2',
		]);

		$this->assertEquals([
			[
				'category' => 'Тестовая категория 1'
			], [
				'category' => 'Тестовая категория 2'
			]
		], $parser->parse());
	}

	public function testJsonDictionary()
	{
		$rules = [
			'internet' => new FromDictionaryRule('dictionaryName', 'other_countries_internet_id')
		];

		$config = (new Config($rules))
			->setBasePath('countries')
			->setLoader(new FileLoader(__DIR__ . '/../../../data/Countries.json'))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);
		$parser->addDictionary('dictionaryName', 'other_countries_internet');

		$this->assertEquals([
			[
				'internet' => ['Германия', 'Греция']
			], [
				'internet' => ['Афганистан', 'Бангладеш']
			]
		], $parser->parse());
	}

	public function testArrayPostProcessing()
	{
		$rules = [
			'category' => new FromDictionaryRule('dictionaryName', 'category', function ($value) {
				return str_replace('Тестовая', 'Какая-то', $value);
			}),
		];

		$config = (new Config($rules))
			->setLoader(new FileLoader(__DIR__ . '/../../../data/Orders.json'))
			->setIgnoreErrors(true);

		$parser = new JsonParser($config);
		$parser->addDictionary('dictionaryName', [
			1 => 'Тестовая категория 1',
			2 => 'Тестовая категория 2',
		]);

		$this->assertEquals([
			[
				'category' => 'Какая-то категория 1'
			], [
				'category' => 'Какая-то категория 2'
			]
		], $parser->parse());
	}

}
