<?php namespace Ipalaus\Geonames;

use Illuminate\Support\ServiceProvider;

class GeonamesServiceProvider extends ServiceProvider {

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
		$this->package('ipalaus/geonames');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['config']->package('ipalaus/geonames', __DIR__ . '/config');

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

		$app->bind('Ipalaus\Geonames\RepositoryInterface', function($app)
		{
			return $app['geonames.repository'];
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

		$app['command.geonames.import'] = $app->share(function($app)
		{
			$config = $app['config']->get('geonames::import', array());

			return new Commands\ImportCommand(new Importer($app['geonames.repository']), $app['files'], $config);
		});

		$app['command.geonames.seed'] = $app->share(function($app)
		{
			return new Commands\SeedCommand($app['db']);
		});

		$app['command.geonames.truncate'] = $app->share(function($app)
		{
			return new Commands\TruncateCommand($app['geonames.repository']);
		});

		$this->commands('command.geonames.install', 'command.geonames.import', 'command.geonames.seed', 'command.geonames.truncate');
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