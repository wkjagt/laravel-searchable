<?php

namespace Searchable;

use Illuminate\Support\Facades\App;

/**
 * Representation of a search request
 *
 * Class SearchRequest
 * @package Searchable
 */
class SearchRequest
{
    /**
     * Add the fully qualified class name of a model to the list of
     * models to search.
     *
     * @param string $model
     */
    public function addModel($model)
    {
        $this->models[] = $model;
    }

    /**
     * The query to use to search on the specified models.
     *
     * @param array $query
     * @return array an array of search results
     */
    public function withQuery(array $query)
    {
        return App::make('searchable.engine')->search($this->models, $query);
    }
} 