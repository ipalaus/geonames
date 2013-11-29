<?php namespace Ipalaus\Geonames\Commands;

use RuntimeException;
use Illuminate\Console\Command;
use Ipalaus\Geonames\RepositoryInterface;

class TruncateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'geonames:truncate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Empties the geoname tables completely.';

	/**
	 * Repository implementation.
	 *
	 * @var \Ipalaus\Geonames\RepositoryInterface
	 */
	protected $repository;

	/**
	 * Create a new console command instance.
	 *
	 * @param  \Ipalaus\Geonames\RepositoryInterface
	 * @return void
	 */
	public function __construct(RepositoryInterface $repository)
	{
		$this->repository = $repository;

		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		if ( ! $this->confirmTruncate())
		{
			throw new RuntimeException('Truncate aborted.');
		}

		$tables = array(
			'geonames_names', 'geonames_alternate_names', 'geonames_countries', 'geonames_language_codes',
			'geonames_admin_divisions', 'geonames_admin_subdivisions', 'geonames_hierarchy',
			'geonames_features', 'geonames_timezones', 'geonames_continents',
		);

		foreach ($tables as $table) {
			$this->repository->truncate($table);

			$this->line("<info>Truncated:</info> $table");
		}
	}

	/**
	 * Ask for confirmation to truncate all tables.
	 *
	 * @return bool
	 */
	protected function confirmTruncate()
	{
		return $this->confirm('Do you wish to truncate all tables? [yes|no]', false);
	}

}