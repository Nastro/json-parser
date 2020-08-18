<?php

namespace JsonParser\Rules;

use JsonParser\Support;

class ArrayRule extends AbstractRule
{
	/** @var string */
	private $path;

	/** @var array */
	private $fields;

	/**
	 * @param string $path
	 * @param array $fields
	 */
	public function __construct($path, array $fields)
	{
		$this->path = $path;
		$this->fields = $fields;
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse(array $item)
	{
		$values = Support::getFromArray($item, $this->path);

		$result = [];
		foreach ($this->fields as $field) {
			$result[] = $values[$field];
		}
		
		return [$result];
	}
}