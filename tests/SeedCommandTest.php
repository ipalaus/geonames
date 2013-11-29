<?php

use Mockery as m;
use Ipalaus\EloquentGeonames\Commands\SeedCommand;

class SeedCommandTest extends PHPUnit_Framework_TestCase {

	/**
	 * @expectedException RuntimeException
	 */
	public function testDevelopmentAndCountryCantBeBothOptions()
	{
		$command = new SeedCommandTestStub($filesystem = m::mock('Illuminate\Filesystem\Filesystem'));

		$this->runCommand($command, array('--development' => true, '--country' => 'IP'));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testMustProvideAValidIsoAlpha2Country()
	{
		$command = new SeedCommandTestStub($filesystem = m::mock('Illuminate\Filesystem\Filesystem'));

		$this->runCommand($command, array('--country' => 'Isern'));
	}

	public function testCommandCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('fileExists', 'downloadFile', 'extractZip'),
			array($filesystem = m::mock('Illuminate\Filesystem\Filesystem')));

		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(false));
		$command->expects($this->atLeastOnce())->method('downloadFile')->will($this->returnValue(null));
		$command->expects($this->atLeastOnce())->method('extractZip')->will($this->returnCallback(function ($path, $filename) {
			return str_replace('.zip', '.txt', $filename);
		}));

		$this->runCommand($command);
	}

	public function testCommandAllFilesExistsCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('fileExists'), array($filesystem = m::mock('Illuminate\Filesystem\Filesystem')));

		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(true));

		$this->runCommand($command);
	}

	public function testDevelopmentOptionCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('downloadFile', 'extractZip'),
			array($filesystem = m::mock('Illuminate\Filesystem\Filesystem')));

		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);

		$command->expects($this->atLeastOnce())->method('downloadFile')->will($this->returnValue(null));
		$command->expects($this->atLeastOnce())->method('extractZip')->will($this->returnCallback(function ($path, $filename) {
			return str_replace('.zip', '.txt', $filename);
		}));

		$this->runCommand($command, array('--development' => true));
	}

	public function testCountryOptionCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('downloadFile', 'extractZip'),
			array($filesystem = m::mock('Illuminate\Filesystem\Filesystem')));

		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);

		$command->expects($this->atLeastOnce())->method('downloadFile')->will($this->returnValue(null));
		$command->expects($this->atLeastOnce())->method('extractZip')->will($this->returnCallback(function ($path, $filename) {
			return str_replace('.zip', '.txt', $filename);
		}));

		$this->runCommand($command, array('--country' => 'IP'));
	}

	public function testFetchOnlyOptionCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('fileExists'), array($filesystem = m::mock('Illuminate\Filesystem\Filesystem')));

		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);
		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(true));

		$this->runCommand($command, array('--fetch-only' => true));
	}

	public function testExtractZipMethod()
	{
		file_put_contents(__DIR__ . '/to_zip.txt', 'Isern Palaus');

		$zip = new ZipArchive;
		$zip->open(__DIR__ . '/zipped.zip', ZipArchive::CREATE);
		$zip->addFile(__DIR__ . '/to_zip.txt', 'been_zipped.txt');
		$zip->close();

		@unlink(__DIR__ . '/to_zip.txt');

		$method = $this->getMethod('extractZip');
		$return = $method->invokeArgs(new SeedCommandTestStub(new Illuminate\Filesystem\Filesystem), array(__DIR__, 'zipped.zip'));

		$this->assertFalse(file_exists(__DIR__ . '/zipped.zip'));
		$this->assertTrue(file_exists(__DIR__ . '/been_zipped.txt'));
		$this->assertTrue($return == 'zipped.txt');

		@unlink(__DIR__ . '/been_zipped.txt');
	}

	/**
	 * @expectedException PHPUnit_Framework_Error_Warning
	 */
	public function testExtractZipMethodThrowsAnExceptionWhenFileIsInvalid()
	{
		$method = $this->getMethod('extractZip');
		$method->invokeArgs(new SeedCommandTestStub($filesystem = m::mock('Illuminate\Filesystem\Filesystem')), array(__DIR__, 'fake.zip'));
	}

	public function testFileExistsMethod()
	{
		$file = __DIR__ . '/exists.txt';
		file_put_contents($file, 'Isern Palaus');

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs(new SeedCommandTestStub($filesystem = m::mock('Illuminate\Filesystem\Filesystem')), array(__DIR__, 'exists.txt'));

		$this->assertTrue($return);
		@unlink($file);
	}

	public function testFileExistsMethodWithZipExtension()
	{
		$file = __DIR__ . '/exists.txt';
		file_put_contents($file, 'Isern Palaus');

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs(new SeedCommandTestStub($filesystem = m::mock('Illuminate\Filesystem\Filesystem')), array(__DIR__, 'exists.zip'));

		$this->assertTrue($return);
		@unlink($file);
	}

	public function testFileExistsMethodReturnFalseWhenNotFound()
	{
		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs(new SeedCommandTestStub($filesystem = m::mock('Illuminate\Filesystem\Filesystem')), array(__DIR__, 'random.txt'));

		$this->assertFalse($return);
	}

	protected function runCommand($command, $options = array())
	{
		return $command->run(new Symfony\Component\Console\Input\ArrayInput($options), new Symfony\Component\Console\Output\NullOutput);
	}

	protected function getMethod($name) {
		$class = new ReflectionClass('SeedCommandTestStub');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

}

class SeedCommandTestStub extends SeedCommand
{

	public function call($command, array $arguments = array())
	{
		//
	}

	public function line($string)
	{
		//
	}

}