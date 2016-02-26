<?php

use Mockery as m;
use Illuminate\Foundation\Application;
use Ipalaus\Geonames\Commands\TruncateCommand;

class TruncateCommandTest extends PHPUnit_Framework_TestCase {

	public function testCommandCall()
	{
		$repo = $this->getMock('Ipalaus\Geonames\RepositoryInterface');
		$repo->expects($this->exactly(10))
			->method('truncate');

		$command = $this->getMock('TruncateCommandTestStub', array('confirmTruncate'), array($repo));

		$app = new Application();
		$command->setLaravel($app);

		$command->expects($this->once())
			->method('confirmTruncate')
			->will($this->returnValue(true));

		$this->runCommand($command);
	}

	public function testConfirmMethodCall()
	{
		$repo = $this->getMock('Ipalaus\Geonames\RepositoryInterface');

		$method = $this->getMethod('confirmTruncate');
		$method->invokeArgs(new TruncateCommandTestStub($repo), array());
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testExistingConfigThrowsException()
	{
		$repo = $this->getMock('Ipalaus\Geonames\RepositoryInterface');

		$command = $this->getMock('TruncateCommandTestStub', array('confirmTruncate'), array($repo));

		$app = new Application();
		$command->setLaravel($app);

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

	public function line($string, $style = null, $verbosity = null)
	{
		//
	}

	public function confirm($string, $default = true)
	{
		return $default;
	}

}