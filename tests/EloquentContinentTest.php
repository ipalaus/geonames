<?php

use Mockery as m;
use Ipalaus\Geonames\Eloquent\Continent;

class EloquentContinentTest extends PHPUnit_Framework_TestCase {

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

	public function testCountriesMethod()
	{
		$model = new Continent;

		$this->addMockConnection($model);

		$stub = $model->countries();

		$this->assertInstanceOf('\Illuminate\Database\Eloquent\Relations\HasMany', $stub);
		$this->assertInstanceOf('\Ipalaus\Geonames\Eloquent\Country', $stub->getQuery()->getModel());
	}

}