<?php

namespace JsonParser;

use JsonParser\Exceptions\InvalidPathNodeException;
use JsonParser\Exceptions\InvalidPathFileException;
use JsonParser\Exceptions\InvalidJsonFormatException;
use JsonParser\Exceptions\UnknownTypeNodeException;

class JsonParser
{
	/** @var string */
	private $path;

	/** @var Config */
	private $config;

	/** @var string */
	private $json;

	/** @var array */
	private $result = [];

	/**
	 * @param string $path
	 * @param array $config
	 */
	function __construct($path, Config $config)
	{
		$this->path = $path;
		$this->config = $config;
	}

	/**
	 * @param string $json
	 * @return JsonParser
	 */
	public function setJson($json)
	{
		$this->parseJson($json);

		return $this;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	private function loadFile($path)
	{
		if (!is_file($path)) {
			throw new InvalidPathFileException(sprintf('Файл %s не существует', $path));
		}

		$content = file_get_contents($path);

		return $content;
	}

	/**
	 * @param string $json
	 * @return bool
	 */
	private function parseJson($json)
	{
		$json = json_decode($json, true);
		if (!$json) {
			throw new InvalidJsonFormatException(sprintf('Проблема с форматом json: %s', $path));
		}

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
	 */
	public function parse()
	{
		if (!$this->json) {
			$json = $this->loadFile($this->path);
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
