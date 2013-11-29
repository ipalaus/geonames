<?php namespace Ipalaus\EloquentGeonames;

use Illuminate\Database\Connection;

class DatabaseRepository implements RepositoryInterface {

	/**
	 * The database connection instance.
	 *
	 * @var \Illuminate\Database\Connection
	 */
	protected $connection;

	/**
	 * Create a new database repository instance.
	 *
	 * @param  \Illuminate\Database\Connection  $connection
	 * @return void
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Truncate the table.
	 *
	 * @param  string  $table
	 * @return void
	 */
	public function truncate($table)
	{
		$this->getTable($table)->truncate();
	}

	/**
	 * Checks if a table is empty.
	 *
	 * @param  string   $table
	 * @return boolean
	 */
	public function isEmpty($table)
	{
		return ($this->getTable($table)->count() === 0);
	}

	/**
	 * Insert an array to a given table.
	 *
	 * @param  string  $table
	 * @param  array   $data
	 * @return void
	 */
	public function insert($table, array $data)
	{
		$this->getTable($table)->insert($data);
	}

	/**
	 * Begin a new database query with the given table.
	 *
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function getTable($table)
	{
		return $this->connection->table($table);
	}

	/**
	 * Get the database connection instance.
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function getConnection()
	{
		return $this->connection;
	}


}