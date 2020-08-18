<?php

namespace JsonParser;

use JsonParser\Rules\IRule;

class Config
{
	/** @var string */
	private $basePath = '';

	/** @var bool */
	private $ignoreErrors = false;

	/** @var array */
	private $rules = [];

	/**
	 * @param IRule[] $rules
	 */
	public function __construct(array $rules)
	{
		$this->rules = $rules;
	}

	/**
	 * @param string $basePath
	 * @return Config
	 */
	public function setBasePath(string $basePath): Config
	{
		$this->basePath = $basePath;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBasePath(): string
	{
		return $this->basePath;
	}

	/**
	 * @param bool $ignoreErrors
	 * @return Config
	 */
	public function setIgnoreErrors(bool $ignoreErrors): Config
	{
		$this->ignoreErrors = $ignoreErrors;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getIgnoreErrors(): bool
	{
		return $this->ignoreErrors;
	}

	/**
	 * @param IRule[] $rules
	 * @return Config
	 */
	public function setRules(array $rules): Config
	{
		$this->rules = $rules;
		return $this;
	}

	/**
	 * @return IRule[]
	 */
	public function getRules(): array
	{
		return $this->rules;
	}

}