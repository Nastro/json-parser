<?php

namespace JsonParser\Loader;

class TextLoader implements ILoader
{
	/**
	 * @var string
	 */
	private $json;

	/**
	 * @param string $json
	 */
	public function __construct(string $json)
	{
		$this->json = $json;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(): ?string
	{
		return $this->json;
	}
}
