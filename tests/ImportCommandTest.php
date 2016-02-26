<?php

use Mockery as m;
use Illuminate\Foundation\Application;
use Ipalaus\Geonames\Importer;
use Ipalaus\Geonames\Commands\ImportCommand;

class ImportCommandTest extends PHPUnit_Framework_TestCase {

	/**
	 * @expectedException RuntimeException
	 */
	public function testDevelopmentAndCountryCantBeBothOptions()
	{
		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $this->getFiles(), array());

		$app = new Application();
		$command->setLaravel($app);

		$this->runCommand($command, array('--development' => true, '--country' => 'IP'));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testMustProvideAValidIsoAlpha2Country()
	{
		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $this->getFiles(), array());

		$app = new Application();
		$command->setLaravel($app);

		$this->runCommand($command, array('--country' => 'Isern'));
	}


	 public function testRunProcessCallsProcess()
	{
		$process = m::mock('Symfony\Component\Process\Process');
		$process->shouldReceive('run')->once();
		$process->shouldReceive('isSuccessful')->once()->andReturn(true);
		$process->shouldReceive('stop')->once();

		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $this->getFiles(), array());

		$method = $this->getMethod('runProcess');
		$return = $method->invokeArgs($command, array($process));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testRunProcessThrowsAnExceptionWhenNotSuccesful()
	{
		$process = m::mock('Symfony\Component\Process\Process');
		$process->shouldReceive('run')->once();
		$process->shouldReceive('isSuccessful')->once()->andReturn(false);
		$process->shouldReceive('getErrorOutput')->once();
		$process->shouldReceive('stop')->once();

		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $this->getFiles(), array());

		$method = $this->getMethod('runProcess');
		$return = $method->invokeArgs($command, array($process));
	}

	public function testCommandCall()
	{
		$filesystem = $this->getFiles();
		$filesystem->shouldReceive('get')->once()->andReturn(file_get_contents(__DIR__ . '/fixtures/names.txt'));

		$repo = $this->getRepo();
		$repo->shouldReceive('isEmpty')->once()->andReturn(true);
		$repo->shouldReceive('insert')->andReturn(null);

		$importer = new Importer($repo);

		$config = array(
			'path'  => __DIR__,
			'files' => array(
				'names'     => 'foobar.txt',
				'countries' => 'ipalaus.zip',
			),
		);

		$mockedMethods = array('fileExists', 'downloadFile', 'extractZip', 'runProcess');

		$command = $this->getMock('ImportCommandTestStub', $mockedMethods, array($importer, $filesystem, $config));

		$app = new Application();
		$command->setLaravel($app);

		$filesystem->shouldReceive('isDirectory')->once()->andReturn(false);
		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);
		$filesystem->shouldReceive('runProcess')->andReturn(null);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(false));
		$command->expects($this->atLeastOnce())->method('downloadFile')->will($this->returnValue(null));
		$command->expects($this->atLeastOnce())->method('extractZip')->will($this->returnCallback(function ($path, $filename) {
			return str_replace('.zip', '.txt', $filename);
		}));

		$this->runCommand($command);
	}

	public function testCommandAllFilesExistsCall()
	{
		$filesystem = $this->getFiles();
		$filesystem->shouldReceive('get')->once()->andReturn(file_get_contents(__DIR__ . '/fixtures/names.txt'));

		$repo = $this->getRepo();
		$repo->shouldReceive('isEmpty')->once()->andReturn(true);
		$repo->shouldReceive('insert')->andReturn(null);

		$importer = new Importer($repo);

		$config = array(
			'path'  => __DIR__,
			'files' => array(
				'names'     => 'foobar.txt',
				'countries' => 'ipalaus.zip',
			),
		);

		$mockedMethods = array('fileExists', 'downloadFile', 'extractZip', 'runProcess');

		$command = $this->getMock('ImportCommandTestStub', $mockedMethods, array($importer, $filesystem, $config));

		$app = new Application();
		$command->setLaravel($app);

		$filesystem->shouldReceive('isDirectory')->once()->andReturn(false);
		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);
		$filesystem->shouldReceive('runProcess')->andReturn(null);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(true));

		$this->runCommand($command);
	}


	public function testFetchOnlyOptionCall()
	{
		$filesystem = $this->getFiles();
		$filesystem->shouldReceive('get')->once()->andReturn(file_get_contents(__DIR__ . '/fixtures/names.txt'));

		$repo = $this->getRepo();
		$repo->shouldReceive('isEmpty')->once()->andReturn(true);
		$repo->shouldReceive('insert')->andReturn(null);

		$importer = new Importer($repo);

		$config = array(
			'path'  => __DIR__,
			'files' => array(
				'names'     => 'foobar.txt',
				'countries' => 'ipalaus.zip',
			),
		);

		$mockedMethods = array('fileExists', 'downloadFile', 'extractZip', 'runProcess');

		$command = $this->getMock('ImportCommandTestStub', $mockedMethods, array($importer, $filesystem, $config));

		$app = new Application();
		$command->setLaravel($app);

		$filesystem->shouldReceive('isDirectory')->once()->andReturn(false);
		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(false));
		$command->expects($this->atLeastOnce())->method('downloadFile')->will($this->returnValue(null));
		$command->expects($this->atLeastOnce())->method('extractZip')->will($this->returnCallback(function ($path, $filename) {
			return str_replace('.zip', '.txt', $filename);
		}));

		$this->runCommand($command, array('--fetch-only' => true));
	}

	public function testDevelopmentOptionCall()
	{
		$filesystem = $this->getFiles();
		$filesystem->shouldReceive('get')->once()->andReturn(file_get_contents(__DIR__ . '/fixtures/names.txt'));

		$repo = $this->getRepo();
		$repo->shouldReceive('isEmpty')->once()->andReturn(true);
		$repo->shouldReceive('insert')->andReturn(null);

		$importer = new Importer($repo);

		$config = array(
			'path'  => __DIR__,
			'files' => array(
				'names'     => 'foobar.txt',
				'countries' => 'ipalaus.zip',
			),
			'development' => 'lighter.one',
		);

		$mockedMethods = array('fileExists', 'downloadFile', 'extractZip', 'runProcess');

		$command = $this->getMock('ImportCommandTestStub', $mockedMethods, array($importer, $filesystem, $config));

		$app = new Application();
		$command->setLaravel($app);

		$filesystem->shouldReceive('isDirectory')->once()->andReturn(false);
		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);
		$filesystem->shouldReceive('runProcess')->andReturn(null);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(true));

		$this->runCommand($command, array('--development' => true));

		// confirm that the name was changed
		$files = $command->getFiles();
		$this->assertTrue($files['names'] === $config['development']);
	}

	public function testCountryOptionCall()
	{
		$filesystem = $this->getFiles();
		$filesystem->shouldReceive('get')->once()->andReturn(file_get_contents(__DIR__ . '/fixtures/names.txt'));

		$repo = $this->getRepo();
		$repo->shouldReceive('isEmpty')->once()->andReturn(true);
		$repo->shouldReceive('insert')->andReturn(null);

		$importer = new Importer($repo);

		$config = array(
			'path'  => __DIR__,
			'files' => array(
				'names'     => 'foobar.txt',
				'countries' => 'ipalaus.zip',
			),
			'wildcard' => '%s',
		);

		$mockedMethods = array('fileExists', 'downloadFile', 'extractZip', 'runProcess');

		$command = $this->getMock('ImportCommandTestStub', $mockedMethods, array($importer, $filesystem, $config));

		$app = new Application();
		$command->setLaravel($app);

		$filesystem->shouldReceive('isDirectory')->once()->andReturn(false);
		$filesystem->shouldReceive('makeDirectory')->once()->andReturn(true);
		$filesystem->shouldReceive('runProcess')->andReturn(null);

		$command->expects($this->atLeastOnce())->method('fileExists')->will($this->returnValue(true));

		$this->runCommand($command, array('--country' => 'IP'));

		// confirm that the name was changed
		$files = $command->getFiles();
		$this->assertTrue($files['names'] === 'IP');
	}

	public function testExtractZipMethod()
	{
		file_put_contents(__DIR__ . '/to_zip.txt', 'Isern Palaus');

		$zip = new ZipArchive;
		$zip->open(__DIR__ . '/zipped.zip', ZipArchive::CREATE);
		$zip->addFile(__DIR__ . '/to_zip.txt', 'been_zipped.txt');
		$zip->close();

		@unlink(__DIR__ . '/to_zip.txt');

		$filesystem = new Illuminate\Filesystem\Filesystem;

		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $filesystem, array());

		$method = $this->getMethod('extractZip');
		$return = $method->invokeArgs($command, array(__DIR__, 'zipped.zip'));

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
		$filesystem = new Illuminate\Filesystem\Filesystem;

		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $filesystem, array());

		$method = $this->getMethod('extractZip');
		$method->invokeArgs($command, array(__DIR__, 'fake.zip'));
	}

	public function testFileExistsMethod()
	{
		$file = __DIR__ . '/exists.txt';
		file_put_contents($file, 'Isern Palaus');

		$command = new ImportCommandTestStub(new Importer($this->getRepo()), $this->getFiles(), array());

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs($command, array(__DIR__, 'exists.txt'));

		$this->assertTrue($return);
		@unlink($file);

		$file = __DIR__ . '/exists.txt';
		file_put_contents($file, 'Isern Palaus');

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs($command, array(__DIR__, 'exists.zip'));

		$this->assertTrue($return);
		@unlink($file);

		$method = $this->getMethod('fileExists');
		$return = $method->invokeArgs($command, array(__DIR__, 'random.txt'));

		$this->assertFalse($return);
	}

	protected function runCommand($command, $options = array())
	{
		return $command->run(new Symfony\Component\Console\Input\ArrayInput($options), new Symfony\Component\Console\Output\NullOutput);
	}

	protected function getMethod($name) {
		$class = new ReflectionClass('ImportCommandTestStub');
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	protected function getFiles()
	{
		return m::mock('Illuminate\Filesystem\Filesystem');
	}

	protected function getRepo()
	{
		return m::mock('Ipalaus\Geonames\RepositoryInterface');
	}

}

class ImportCommandTestStub extends ImportCommand
{

	public function call($command, array $arguments = array())
	{
		//
	}

	public function line($string, $style = null, $verbosity = null)
	{
		//
	}

}
