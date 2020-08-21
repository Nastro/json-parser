<?php

namespace JsonParser;

use JsonParser\Rules\IRule;
use JsonParser\Loader\ILoader;

class Config
{
	/** @var string */
	private $basePath = '';

	/** @var bool */
	private $ignoreErrors = false;

	/** @var IRule[] */
	private $rules = [];

	/** @var array */
	private $dictionaries = [];

	/** @var ILoader */
	private $loader;

	/**
	 * @param IRule[] $rules
	 */
	public function __construct(array $rules)
	{
		$this->rules = $rules;
	}

	/**
	 * @param string $name
	 * @param Dictionary $dictionary
	 * @return Config
	 */
	public function addDictionary(string $name, Dictionary $dictionary)
	{
		$this->dictionaries[$name] = $dictionary;
		return $this;
	}

	/**
	 * @param strign $name
	 * @return array
	 */
	public function getDictionary(string $name)
	{
		return $this->dictionaries[$name] ?? null;
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

	/**
	 * @return ILoader
	 */
	public function getLoader(): ILoader
	{
		return $this->loader;
	}

	/**
	 * @param ILoader $loader
	 * @return Config
	 */
	public function setLoader(ILoader $loader): Config
	{
		$this->loader = $loader;
		return $this;
	}

}
