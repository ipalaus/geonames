<?php namespace Ipalaus\Geonames\Commands;

use ZipArchive;
use ErrorException;
use RuntimeException;
use Ipalaus\Geonames\Importer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class ImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'geonames:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import and seed the geonames database with fresh records.';

	/**
	 * Importer instance.
	 *
	 * @var \Ipalaus\Geonames\Importer
	 */
	protected $importer;

	/**
	 * Filesystem implementation.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

	/**
	 * File archive instance.
	 *
	 * @var \ZipArchive
	 */
	protected $archive;

	/**
	 * Configuration options.
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * Create a new console command instance.
	 *
	 * @param  \Ipalaus\Geonames\Importer         $importer
	 * @param  \Illuminate\Filesystem\Filesystem  $filesystem
	 * @return void
	 */
	public function __construct(Importer $importer, Filesystem $filesystem, array $config)
	{
		$this->importer = $importer;
		$this->filesystem = $filesystem;
		$this->config = $config;

		$this->archive = new ZipArchive;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$country     = $this->input->getOption('country');
		$development = $this->input->getOption('development');
		$fetchOnly   = $this->input->getOption('fetch-only');
		$wipeFiles   = $this->input->getOption('wipe-files');

		// i'm sorry but you can't have both :(
		if ($development and ! is_null($country)) {
			throw new RuntimeException("You have to select between a country or a development version of GeoNames.");
		}

		// set a development names, lighter download
		$development and $this->setDevelopment();

		// set a specific country names
		$country and $this->setCountry($country);

		// path to download our files
		$path = $this->getPath();

		// if we forced to wipe files, we will delete the directory
		$wipeFiles and $this->filesystem->deleteDirectory($path);

		// create the directory if it doesn't exists
		if ( ! $this->filesystem->isDirectory($path)) {
			$this->filesystem->makeDirectory($path);
		}

		$files = $this->getFiles();

		// loop all the files that we need to donwload
		foreach ($files as $file) {
			$filename = basename($file);

			if ($this->fileExists($path, $filename)) {
				$this->line("<info>File exists:</info> $filename");

				continue;
			}

			$this->line("<info>Downloading:</info> $file");

			$this->downloadFile($file, $path, $filename);

			// if the file is ends with zip, we will try to unzip it and remove the zip file
			if (substr($filename, -strlen('zip')) === "zip") {
				$this->line("<info>Unzip:</info> $filename");
				$filename = $this->extractZip($path, $filename);
			}
		}

		// if we only want to fetch files, we must stop the execution of the command
		if ($fetchOnly) {
			$this->line('<info>Files fetched.</info>');
			return;
		}

		$this->line("<info>It is time to seed the database. This may take 'a while'...</info>");

		// finally seed the common seeders
		$this->seedCommand('ContinentsTableSeeder');
		$this->seedCommand('CountriesTableSeeder');
		$this->seedCommand('AdminDivionsTableSeeder');
		$this->seedCommand('AdminSubdivionsTableSeeder');
		$this->seedCommand('HierarchiesTableSeeder');
		$this->seedCommand('FeaturesTableSeeder');
		$this->seedCommand('TimezonesTableSeeder');

		// depending if we run a country, development or plain names we will run
		// different seeders. Note that the langauge codes file is only
		// available with the allCountries.zip file.
		if ($development) {
			$this->seedCommand('DevelopmentNamesTableSeeder');
		} elseif ($country) {
			$this->seedCommand('CountryNamesTableSeeder', '--country=' . $country);
		} else {
			$this->seedCommand('NamesTableSeeder');
			$this->seedCommand('AlternateNamesTableSeeder');
			$this->seedCommand('LanguageCodesTableSeeder');
		}
	}

	/**
	 * Run a seed coman in a separate process.
	 *
	 * @param  string  $class
	 * @return void
	 */
	protected function seedCommand($class, $extra = '')
	{
		$string = 'php artisan geonames:seed --class="Ipalaus\Geonames\Seeders\%s" --path="%s" ' . $extra;

		$command = sprintf($string, $class, $this->getPath());

		$process = new Process($command, $this->laravel['path.base'], null, null, 600);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
			throw new \RuntimeException($process->getErrorOutput());
		}

		$this->line("<info>Seeded:</info> $class");
	}

	/**
	 * Sets the names file for a ligher version for development. We also ignore
	 * the alternate names.
	 *
	 * @return void
	 */
	protected function setDevelopment()
	{
		$this->config['files']['names'] = $this->config['development'];

		unset($this->config['files']['alternate']);
	}

	/**
	 * Sets the name file to a specific country.
	 *
	 * @param  string $country
	 * @return void
	 */
	protected function setCountry($country)
	{
		if (strlen($country) > 2) {
			throw new RuntimeException('Country format must be in ISO Alpha 2 code.');
		}

		$this->config['files']['names'] = sprintf($this->config['wildcard'], strtoupper($country));

		unset($this->config['files']['alternate']);
	}

	/**
	 * Download a file from a remote URL to a given path.
	 *
	 * @param  string  $url
	 * @param  string  $path
	 * @return void
	 */
	protected function downloadFile($url, $path, $filename)
	{
		if ( ! $fp = fopen ($path . '/' . $filename, 'w+')) {
			throw new RuntimeException('Impossible to write to path: ' . $path);
		}

		// curl looks like shit but whatever
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}

	/**
	 * Given a zip archive, extract a the file and remove the original.
	 *
	 * @param  string  $path
	 * @param  string  $filename
	 * @return string
	 */
	protected function extractZip($path, $filename)
	{
		$this->archive->open($path . '/' . $filename);

		$this->archive->extractTo($path . '/');
		$this->archive->close();

		$this->filesystem->delete($path . '/' . $filename);

		return str_replace('.zip', '.txt', $filename);
	}

	/**
	 * Checks if a file already exists on a path. If the file contains .zip in
	 * the name we will also check for matches with .txt.
	 *
	 * @param  string  $path
	 * @param  string  $filename
	 * @return bool
	 */
	protected function fileExists($path, $filename)
	{
		if (file_exists($path . '/' . $filename)) {
			return true;
		}

		if (file_exists($path . '/' . str_replace('.zip', '.txt', $filename))) {
			return true;
		}

		return false;
	}

	protected function getPath()
	{
		return $this->config['path'];
	}

	/**
	 * Get the files to download.
	 *
	 * @return array
	 */
	protected function getFiles()
	{
		return $this->config['files'];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('country', null, InputOption::VALUE_REQUIRED, 'Downloads just the specific country.'),
			array('development', null, InputOption::VALUE_NONE, 'Downloads an smaller version of names (~10MB).'),
			array('fetch-only', null, InputOption::VALUE_NONE, 'Just download the files.'),
			array('force', 'f', InputOption::VALUE_NONE, 'Forces overwriting the downloaded files.'),
			array('wipe-files', null, InputOption::VALUE_NONE, 'Wipe old downloaded files and fetch new ones.'),
		);
	}

}