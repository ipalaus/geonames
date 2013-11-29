<?php namespace Ipalaus\EloquentGeonames;

interface RepositoryInterface {

	public function truncate($table);
	public function isEmpty($table);
	public function insert($table, array $data);

}