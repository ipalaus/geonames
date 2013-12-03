<?php

use Mockery as m;
use Ipalaus\Geonames\Eloquent\Name;

class EloquentNameTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
    {
        m::close();
    }

	/**
	* Set mock connection
	*/
	protected function addMockConnection($model)
	{
		$resolver = m::mock('Illuminate\Database\ConnectionResolverInterface');
		$model->setConnectionResolver($resolver);
		$resolver->shouldReceive('connection')->andReturn(m::mock('Illuminate\Database\Connection'));
		$model->getConnection()->shouldReceive('getQueryGrammar')->andReturn(m::mock('Illuminate\Database\Query\Grammars\Grammar'));
		$model->getConnection()->shouldReceive('getPostProcessor')->andReturn(m::mock('Illuminate\Database\Query\Processors\Processor'));
	}

	public function testContinentMethod()
	{
		$model = new Name;

		$this->addMockConnection($model);

		$stub = $model->country();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\BelongsTo', $stub);
		$this->assertInstanceOf('\Ipalaus\Geonames\Eloquent\Country', $stub->getQuery()->getModel());
	}

}