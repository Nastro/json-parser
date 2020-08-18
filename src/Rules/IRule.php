<?php

namespace JsonParser\Rules;

use JsonParser\Config;

interface IRule
{
	/**
	 * @param array $item
	 * @return mixed
	 */
	public function parse(array $item);

	/**
	 * @param Config $config
	 */
	public function setConfig(Config $config): IRule;
}
