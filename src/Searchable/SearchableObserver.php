<?php

namespace Searchable;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;

/**
 * Class SearchableObserver
 *
 * A generic observer that can be used for any eloquent model that passed the
 * 'created', 'updated' and 'deleted' events on to a configured search engine
 * object.
 *
 * @package Searchable
 */
class SearchableObserver
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Listener for the 'created' event on a model.
     *
     * @param $model the model that fires the events
     */
    public function created($model)
    {
        $this->app->make('searchable.engine')->index($model);
    }

    /**
     * Listener for the 'updated' event on a model.
     *
     * @param $model the model that fires the events
     */
    public function updated($model)
    {
        $this->app->make('searchable.engine')->update($model);
    }

    /**
     * Listener for the 'deleted' event on a model.
     *
     * @param $model the model that fires the events
     */
    public function deleted($model)
    {
        $this->app->make('searchable.engine')->delete($model);
    }
}