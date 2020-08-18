<?php

namespace Tests\unit\JsonParser;

use PHPUnit\Framework\TestCase;
use JsonParser\Support;

class SupportTest extends TestCase
{
	public function testGetFromArray()
	{
		$data = ['root' => ['child' => 'test']];
		$value = Support::getFromArray($data, 'root.child');
		$this->assertSame('test', $value);
	}
}
