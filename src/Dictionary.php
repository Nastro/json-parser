<?php

namespace JsonParser;

use JsonParser\Exceptions\UnknownDictionaryException;

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
	 * @throws UnknownDictionaryException
	 */
	public function getList()
	{
		switch (true) {
			case is_array($this->source):
				return $this->source;
			case is_string($this->source):
				return Support::getFromArray($this->parser->getOriginalJson(), $this->source, []);
			default:
				throw new UnknownDictionaryException('Неизвестный тип справочника');
		}
	}
}
