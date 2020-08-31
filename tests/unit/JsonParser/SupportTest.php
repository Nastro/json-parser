<?php

namespace Tests\unit\JsonParser;

use PHPUnit\Framework\TestCase;
use JsonParser\Support;

class SupportTest extends TestCase
{
	public function testGetFromArraySuccess()
	{
		$data = ['root' => ['child' => 'test1']];
		$value = Support::getFromArray($data, 'root.child');
		$this->assertSame('test', $value);
	}

	public function testGetFromArrayDefaultValue()
	{
		$data = ['root' => ['child' => 'test']];
		$value = Support::getFromArray($data, 'random.path', 'default_value');
		$this->assertSame('default_value', $value);
	}

	public function testGetFromArrayNullValue()
	{
		$data = ['root' => ['child' => 'test']];
		$value = Support::getFromArray($data, 'random.path');
		$this->assertNull($value);
	}
}
