<?php

use Ipalaus\Geonames\Commands\InstallCommand;

class InstallCommandTest extends PHPUnit_Framework_TestCase {

	public function testCommandCall()
	{
		$command = new InstallCommandTestStub;

		$this->runCommand($command);
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testExistingConfigThrowsException()
	{
		$command = $this->getMock('InstallCommandTestStub', array('configExists'));
		$command->expects($this->once())
				->method('configExists')
				->will($this->returnValue(true));

		$this->runCommand($command);
	}

	public function testForceEvenConfigExists()
	{
		$command = $this->getMock('InstallCommandTestStub', array('configExists'));
		$command->expects($this->once())
				->method('configExists')
				->will($this->returnValue(true));

		$this->runCommand($command, array('--force' => true));
	}

	protected function runCommand($command, $options = array())
	{
		return $command->run(new Symfony\Component\Console\Input\ArrayInput($options), new Symfony\Component\Console\Output\NullOutput);
	}

}

class InstallCommandTestStub extends InstallCommand
{

	public function call($command, array $arguments = array())
	{
		//
	}
}