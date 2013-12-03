<?php

use Mockery as m;
use Ipalaus\Geonames\Importer;

class ImporterTest extends PHPUnit_Framework_TestCase {

	public function testMethodNames()
	{
		$importer = $this->getImporter();
		$importer->names('isern', __DIR__ . '/fixtures/names.txt');
	}

	public function testMethodCountries()
	{
		$importer = $this->getImporter();
		$importer->countries('isern', __DIR__ . '/fixtures/countries.txt');
	}

	public function testMethodLanguageCodes()
	{
		$importer = $this->getImporter();
		$importer->languageCodes('isern', __DIR__ . '/fixtures/languages.txt');
	}

	public function testMethodAdminDivions()
	{
		$importer = $this->getImporter();
		$importer->adminDivions('isern', __DIR__ . '/fixtures/divisions.txt');
	}

	public function testMethodHierarchies()
	{
		$importer = $this->getImporter();
		$importer->hierarchies('isern', __DIR__ . '/fixtures/hierarchies.txt');
	}

	public function testMethodFeatures()
	{
		$importer = $this->getImporter();
		$importer->features('isern', __DIR__ . '/fixtures/features.txt');
	}

	public function testMethodTimezones()
	{
		$importer = $this->getImporter();
		$importer->timezones('isern', __DIR__ . '/fixtures/timezones.txt');
	}

	public function testMethodAlternateNames()
	{
		$importer = $this->getImporter();
		$importer->alternateNames('isern', __DIR__ . '/fixtures/alternates.txt');
	}

	public function testMethodContinents()
	{
		$importer = $this->getImporter();
		$importer->continents('isern', array(array('EU', 'Europe', 6255148)));
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testNotEmptyTableThrowsAnException()
	{
		$repo = m::mock('Ipalaus\Geonames\RepositoryInterface');
		$repo->shouldReceive('isEmpty')->once()->andReturn(false);

		$importer = new Importer($repo);
		$importer->names('isern', __DIR__);
	}

	protected function getImporter()
	{
		$repo = m::mock('Ipalaus\Geonames\RepositoryInterface');
		$repo->shouldReceive('isEmpty')->once()->andReturn(true);
		$repo->shouldReceive('insert')->once();

		return new Importer($repo);
	}

}