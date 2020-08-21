<?php

namespace JsonParser;

use JsonParser\Exceptions\InvalidPathNodeException;
use JsonParser\Exceptions\InvalidPathFileException;
use JsonParser\Exceptions\InvalidJsonFormatException;
use JsonParser\Exceptions\UnknownTypeNodeException;

class JsonParser
{
	/** @var Config */
	private $config;

	/** @var array */
	private $json;

	/** @var array */
	private $originalJson;

	/** @var array */
	private $result = [];

	/** @var array */
	private $dictionaries = [];

	/**
	 * @param Config $config
	 */
	function __construct(Config $config)
	{
		$this->config = $config;
	}

	/**
	 * @param string $name
	 * @param mixed $source
	 * @return JsonParser
	 */
	public function addDictionary(string $name, $source)
	{
		$this->dictionaries[$name] = new Dictionary($name, $source, $this);
		$this->config->addDictionary($name, $this->dictionaries[$name]);
		return $this;
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function getDictionary(string $name)
	{
		return $this->dictionaries[$name] ?? null;
	}

	/**
	 * @return array
	 */
	public function getDictionaries()
	{
		return $this->dictionaries;
	}


	/**
	 * @param string $json
	 * @return JsonParser
	 * @throws InvalidJsonFormatException
	 * @throws InvalidPathNodeException
	 */
	public function setJson(string $json)
	{
		$this->parseJson($json);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getJson()
	{
		return $this->json;
	}

	/**
	 * @return array
	 */
	public function getOriginalJson()
	{
		return $this->originalJson;
	}

	/**
	 * @param string $json
	 * @return bool
	 * @throws InvalidJsonFormatException
	 * @throws InvalidPathNodeException
	 */
	private function parseJson($json)
	{
		$json = json_decode($json, true);
		if (!$json) {
			throw new InvalidJsonFormatException('Проблема с форматом json');
		}

		$this->originalJson = $json;

		$basePath = $this->config->getBasePath();
		if ($basePath) {
			$json = Support::getFromArray($json, $basePath);
		}

		if (!$json) {
			throw new InvalidPathNodeException(sprintf('Нода с базовым путем %s не существует', $basePath));
		}

		$this->json = $json;

		return true;
	}

	/**
	 * @return array
	 * @throws InvalidJsonFormatException
	 * @throws InvalidPathNodeException
	 */
	public function parse()
	{
		if (!$this->json) {
			$json = $this->config->getLoader()->load();
			$this->parseJson($json);
		}

		foreach ($this->json as $item) {
			$this->result[] = $this->parseItem($item);
		}

		$this->json = null;
		return $this->result;
	}

	/**
	 * @param array $item
	 * @return array
	 */
	private function parseItem(array $item)
	{
		$result = [];
		foreach ($this->config->getRules() as $name => $rule) {
			$result[$name] = $rule->setConfig($this->config)->parse($item);
		}

		return $result;
	}

}
