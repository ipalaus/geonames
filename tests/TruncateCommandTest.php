<?php

use Mockery as m;
use Ipalaus\EloquentGeonames\Commands\TruncateCommand;

class TruncateCommandTest extends PHPUnit_Framework_TestCase {

	public function testCommandCall()
	{
		$repo = $this->getMock('Ipalaus\EloquentGeonames\RepositoryInterface', array('truncate'));
		$repo->expects($this->exactly(10))
			->method('truncate');

		$command = $this->getMock('TruncateCommandTestStub', array('confirmTruncate'), array($repo));
		$command->expects($this->once())
			->method('confirmTruncate')
			->will($this->returnValue(true));

		$this->runCommand($command);
	}

	public function testConfirmMethodCall()
	{
		$repo = $this->getMock('Ipalaus\EloquentGeonames\RepositoryInterface');

		$method = $this->getMethod('confirmTruncate');
		$method->invokeArgs(new TruncateCommandTestStub($repo), array());
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testExistingConfigThrowsException()
	{
		$repo = $this->getMock('Ipalaus\EloquentGeonames\RepositoryInterface', array('truncate'));

		$command = $this->getMock('TruncateCommandTestStub', array('confirmTruncate'), array($repo));
		$command->expects($this->once())
				->method('confirmTruncate')
				->will($this->returnValue(false));

		$this->runCommand($command);
	}

	protected function runCommand($command, $options = array())
	{
		return $command->run(new Symfony\Component\Console\Input\ArrayInput($options), new Symfony\Component\Console\Output\NullOutput);
	}

	protected function getMethod($name) {
		$class = new ReflectionClass('TruncateCommandTestStub');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

}

class TruncateCommandTestStub extends TruncateCommand {

	public function line($string)
	{
		//
	}

	public function confirm($string, $default = true)
	{
		return $default;
	}

}