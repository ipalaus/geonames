<?php namespace Ipalaus\Geonames;

interface RepositoryInterface {

	public function truncate($table);
	public function isEmpty($table);
	public function count($table);
	public function insert($table, array $data);

}