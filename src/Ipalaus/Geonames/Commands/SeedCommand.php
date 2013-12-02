<?php namespace Ipalaus\Geonames\Commands;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\ConnectionResolverInterface as Resolver;
use Illuminate\Database\Console\SeedCommand as IlluminateSeedCommand;

class SeedCommand extends IlluminateSeedCommand {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'geonames:seed';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Seed the database with records';

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array_merge(array(
			array('country', null, InputOption::VALUE_REQUIRED, 'Seed a specific country instead.'),
			array('path', null, InputOption::VALUE_REQUIRED, 'Path where files are located.'),
		), parent::getOptions());
	}

}