<?php

namespace JsonParser\Loader;

interface ILoader
{
	/**
	 * @return string|null
	 */
	public function load(): ?string;
}
