<?php

namespace JsonParser\Rules;

class CallableRule extends AbstractRule
{
	/** @var callable */
	private $func;

	/**
	 * @param callable $func
	 */
	public function __construct($func)
	{
		$this->func = $func;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse(array $item)
	{
		return call_user_func_array($this->func, [$item]);
	}
}