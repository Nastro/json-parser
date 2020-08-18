<?php

namespace JsonParser\Rules;

use JsonParser\Config;

abstract class AbstractRule implements IRule
{
	/** @var Config */
	protected $config;

	public function setConfig(Config $config): IRule
	{
		$this->config = $config;
		return $this;
	}

	public function create(...$args)
	{
		return new static(...$args);
	}

}
