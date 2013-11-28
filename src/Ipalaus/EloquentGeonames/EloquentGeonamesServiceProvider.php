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
		$this->registerCommands();
	}

	/**
	 * Register the auth related console commands.
	 *
	 * @return void
	 */
	protected function registerCommands()
	{
		$app = $this->app;

		$app['command.ipalaus.geonames.install'] = $app->share(function($app)
		{
			return new Commands\InstallCommand;
		});

		$this->commands('command.ipalaus.geonames.install');
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