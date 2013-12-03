<?php

use Mockery as m;

class DatabaseRepositoryTest extends PHPUnit_Framework_TestCase {

	public function testTruncateMethodTruncateTable()
	{
		$repo = new Ipalaus\Geonames\DatabaseRepository(m::mock('Illuminate\Database\Connection'));
		$repo->getConnection()->shouldReceive('table')->once()->with('isern')->andReturn($query = m::mock('StdClass'));
		$query->shouldReceive('truncate')->once();

		$repo->truncate('isern');
	}

	public function testIsEmptyMethod()
	{
		$repo = new Ipalaus\Geonames\DatabaseRepository(m::mock('Illuminate\Database\Connection'));
		$repo->getConnection()->shouldReceive('table')->once()->with('isern')->andReturn($query = m::mock('StdClass'));
		$query->shouldReceive('count')->once()->andReturn(1);

		$repo->isEmpty('isern');
	}

}