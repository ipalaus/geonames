<?php namespace Ipalaus\Geonames;

use RuntimeException;
use Illuminate\Filesystem\Filesystem;

class Importer {

	/**
	 * Repository implementation.
	 *
	 * @var \Ipalaus\Geonames\RepositoryInterface
	 */
	protected $repository;

	/**
	 * Filesystem implementation.
	 *
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $filesystem;

	/**
	 * Create a new instance of Importer.
	 *
	 * @param  \Ipalaus\Geonames\RepositoryInterface  $repository
	 * @param  \Illuminate\Filesystem\Filesystem              $filesystem
	 * @return void
	 */
	public function __construct(RepositoryInterface $repository, Filesystem $filesystem)
	{
		$this->repository = $repository;
		$this->filesystem = $filesystem;
	}

	/**
	 * Parse the names file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function names($table, $path)
	{
		$this->isEmpty($table);

		$rows = $this->parseFile($path);

		foreach ($rows as $row) {
			$insert = array(
				'id'              => $row[0],
				'name'            => $row[1],
				'ascii_name'      => $row[2],
				'alternate_names' => $row[3],
				'latitude'        => $row[4],
				'longitude'       => $row[5],
				'f_class'         => $row[6],
				'f_code'          => $row[7],
				'country_id'      => $row[8],
				'cc2'             => $row[9],
				'admin1'          => $row[10],
				'admin2'          => $row[11],
				'admin3'          => $row[12],
				'admin4'          => $row[13],
				'population'      => $row[14],
				'elevation'       => $row[15],
				'gtopo30'         => $row[16],
				'timezone_id'     => $row[17],
				'modification_at' => $row[18],
			);

			$this->repository->insert($table, $insert);
		}
	}

	/**
	 * Parse the countries file and inserts it to the database.
	 *
	 * @param  string  $table
	 * @param  string  $path
	 * @return void
	 */
	public function countries($table, $path)
	{
		$this->isEmpty($table);

		$rows = $this->parseFile($path);

		foreach ($rows as $row) {
			$insert = array(
				'iso_alpha2'           => $row[0],
				'iso_alpha3'           => $row[1],
				'iso_numeric'          => $row[2],
				'fips_code'            => $row[3],
				'name'                 => $row[4],
				'capital'              => $row[5],
				'area'                 => $row[6],
				'population'           => $row[7],
				'continent'            => $row[8],
				'tld'                  => $row[9],
				'currency'             => $row[10],
				'currency_name'        => $row[11],
				'phone'                => $row[12],
				'postal_code_format'   => $row[13],
				'postal_code_regex'    => $row[14],
				'name_id'              => $row[15],
				'languages'            => $row[16],
				'neighbours'           => $row[17],
				'equivalent_fips_code' => $row[18],
			);

			$this->repository->insert($table, $insert);
		}
	}

	/**
	 * Prevent wrong executons of the importer.
	 *
	 * @param  string   $table
	 * @return void
	 */
	protected function isEmpty($table)
	{
		if ( ! $this->repository->isEmpty($table)) {
			throw new RuntimeException("The table [$table] is not empty.");
		}
	}

	/**
	 * Parse a given file and return the CSV lines as an array.
	 *
	 * @param  string $path
	 * @return array
	 */
	protected function parseFile($path)
	{
		$content = $this->filesystem->get($path);

		$rows = array();

		foreach (explode(PHP_EOL, $content) as $row) {
			// ignore the comment lines in the file
			if ($row === '' or strpos($row, '#') === 0) continue;

			$rows[] = explode("\t", $row);
		}

		return $rows;
	}

}