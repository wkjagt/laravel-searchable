<?php namespace Searchable;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Searchable\Command\IndexAll;

class SearchableServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('wkjagt/searchable');
        $this->app['config']->package('wkjagt/searchable', __DIR__.'/../../config');

        $this->registerCommands();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Searchable\Engines\SearchEngineInterface', 'Searchable\Engines\ElasticSearchEngine');

        $this->app->bind('searchable', function()
        {
            return new Searchable;
        });

        $this->app->singleton('searchable.engine', function($app)
        {
            return $app->make('Searchable\Engines\SearchEngineInterface');
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('searchable', 'searchable.engine');
    }

    /**
     * Register console command bindings.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->app->bindIf('command.searchable.indexall', function () {
            return new IndexAll();
        });

        $this->commands('command.searchable.indexall');
    }
}
