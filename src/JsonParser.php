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

	private function loadFile($path)
	{
		if (!is_file($path)) {
			throw new InvalidPathFileException(sprintf('Файл %s не существует', $path));
		}

		$content = file_get_contents($path);
		$json = json_decode($content, true);
		if (!$json) {
			throw new InvalidJsonFormatException(sprintf('Проблема с форматом json: %s', $path));

		}

		$basePath = $this->config->getBasePath();
		if ($basePath) {
			$json = $json[$basePath] ?? '';
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
			$this->loadFile($this->path);
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

	// /**
	//  * @param callable $func
	//  * @param array $from
	//  * @return mixed
	//  */
	// private function parseCallableValue(callable $func, array $from)
	// {
	// 	return $func($from);
	// }

	// /**
	//  * @param string $path
	//  * @param array $from
	//  * @return mixed
	//  */ 
	// private function parseStringValue(string $path, array $from)
	// {
	// 	$paths = explode('.', $path);
	// 	$currentFrom = $from;

	// 	foreach ($paths as $pathChunk) {
	// 		if (!isset($currentFrom[$pathChunk]) && $this->config->getIgnoreErrors()) {
	// 			continue;
	// 		} elseif (!isset($currentFrom[$pathChunk])) {
	// 			throw new InvalidPathNodeException(sprintf('Нода %s не существует', $path));
	// 		}

	// 		$currentFrom = $currentFrom[$pathChunk];
	// 	}

	// 	return $currentFrom;
	// }

}
