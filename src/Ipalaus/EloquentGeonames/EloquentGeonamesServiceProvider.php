<?php namespace Ipalaus\EloquentGeonames;

use Illuminate\Support\ServiceProvider;

class EloquentGeonamesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('ipalaus/eloquent-geonames');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerRepository();
		$this->registerCommands();
	}

	/**
	 * Register the repository implementation.
	 *
	 * @return void
	 */
	protected function registerRepository()
	{
		$app = $this->app;

		$app['geonames.repository'] = $app->share(function($app)
		{
			$connection = $app['db']->connection();

			return new DatabaseRepository($connection);
		});
	}

	/**
	 * Register the auth related console commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$app = $this->app;

		$app['command.geonames.install'] = $app->share(function($app)
		{
			return new Commands\InstallCommand;
		});

		$app['command.geonames.seed'] = $app->share(function($app)
		{
			return new Commands\SeedCommand;
		});

		$this->commands('command.geonames.install', 'command.geonames.seed');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}