<?php

namespace JsonParser;

use Exception;

class Dictionary
{
	/** @var string */
	private $name;

	/** @var mixed */
	private $source;

	/** @var JsonParser */
	private $parser;

	/**
	 * @param string $name
	 * @param mixed $source
	 */
	public function __construct($name, $source, JsonParser $parser)
	{
		$this->name = $name;
		$this->source = $source;
		$this->parser = $parser;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getList()
	{
		switch (true) {
			case is_array($this->source):
				return $this->source;
			case is_string($this->source):
				return Support::getFromArray($this->parser->getOriginalJson(), $this->source, []);
			default:
				throw new Exception('Неизвестный тип справочника');
		}
	}
}
