<?php

namespace JsonParser\Rules;

use JsonParser\Exceptions\InvalidPathNodeException;
use JsonParser\Support;

class DotPathRule extends AbstractRule
{
	/** @var string */
	private $path;

	/**
	 * @param string $path
	 */
	public function __construct(string $path)
	{
		$this->path = $path;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse(array $item)
	{
		$value = Support::getFromArray($item, $this->path);

		if (!$value && !$this->config->getIgnoreErrors()) {
			throw new InvalidPathNodeException(sprintf('Нода %s не существует', $path));
		}

		return $value;
	}
}