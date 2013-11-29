<?php

use Ipalaus\EloquentGeonames\Commands\SeedCommand;

class SeedCommandTest extends PHPUnit_Framework_TestCase {

	/**
	 * @expectedException RuntimeException
	 */
	public function testDevelopmentAndCountryCantBeBothOptions()
	{
		$command = new SeedCommandTestStub;

		$this->runCommand($command, array('--development' => true, '--country' => 'IP'));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testMustProvideAValidIsoAlpha2Country()
	{
		$command = new SeedCommandTestStub;

		$this->runCommand($command, array('--country' => 'Isern'));
	}

	public function testCommandCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('makeDirectory', 'fileExists', 'downloadFile', 'extractZip'));

		$command->expects($this->once())
			->method('makeDirectory')
			->will($this->returnValue(true));

		$command->expects($this->atLeastOnce())
			->method('fileExists')
			->will($this->returnCallback(function () {
				return (bool) rand(0, 1); // this must be improved in a separated test case
			}));

		$command->expects($this->atLeastOnce())
			->method('downloadFile')
			->will($this->returnValue(null));

		$command->expects($this->atLeastOnce())
			->method('extractZip')
			->will($this->returnCallback(function ($path, $filename) {
				return str_replace('.zip', '.txt', $filename);
			}));

		$this->runCommand($command);
	}

	public function testDevelopmentOptionCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('makeDirectory', 'downloadFile', 'extractZip'));

		$command->expects($this->once())
			->method('makeDirectory')
			->will($this->returnValue(true));

		$command->expects($this->atLeastOnce())
			->method('downloadFile')
			->will($this->returnValue(null));

		$command->expects($this->atLeastOnce())
			->method('extractZip')
			->will($this->returnCallback(function ($path, $filename) {
				return str_replace('.zip', '.txt', $filename);
			}));

		$this->runCommand($command, array('--development' => true));
	}

	public function testCountryOptionCall()
	{
		$command = $this->getMock('SeedCommandTestStub', array('makeDirectory', 'downloadFile', 'extractZip'));

		$command->expects($this->once())
			->method('makeDirectory')
			->will($this->returnValue(true));

		$command->expects($this->atLeastOnce())
			->method('downloadFile')
			->will($this->returnValue(null));

		$command->expects($this->atLeastOnce())
			->method('extractZip')
			->will($this->returnCallback(function ($path, $filename) {
				return str_replace('.zip', '.txt', $filename);
			}));

		$this->runCommand($command, array('--country' => 'IP'));
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
		$return = $method->invokeArgs(new SeedCommandTestStub, array(__DIR__, 'zipped.zip'));

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
		$method->invokeArgs(new SeedCommandTestStub, array(__DIR__, 'fake.zip'));
	}

	public function testMakeDirectoryMethod()
	{
		$dir = __DIR__ . '/test';

		$method = $this->getMethod('makeDirectory');
		$method->invokeArgs(new SeedCommandTestStub, array($dir));

		$this->assertTrue(is_dir($dir));

		@rmdir($dir);
	}

	public function testDeleteFileMethod()
	{
		$file = __DIR__ . '/delete.txt';
		file_put_contents($file, 'Isern Palaus');

		$method = $this->getMethod('deleteFile');
		$method->invokeArgs(new SeedCommandTestStub, array($file));

		$this->assertFalse(file_exists($file));
	}

	public function testFileExistsMethod()
	{
		$file = __DIR__ . '/exists.txt';
		file_put_contents($file, 'Isern Palaus');

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs(new SeedCommandTestStub, array(__DIR__, 'exists.txt'));

		$this->assertTrue($return);
		@unlink($file);
	}

	public function testFileExistsMethodWithZipExtension()
	{
		$file = __DIR__ . '/exists.txt';
		file_put_contents($file, 'Isern Palaus');

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs(new SeedCommandTestStub, array(__DIR__, 'exists.zip'));

		$this->assertTrue($return);
		@unlink($file);
	}

	public function testFileExistsMethodReturnFalseWhenNotFound()
	{
		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs(new SeedCommandTestStub, array(__DIR__, 'random.txt'));

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