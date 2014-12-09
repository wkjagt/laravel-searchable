<?php

namespace Searchable;

use Illuminate\Support\Facades\App;

class SuggestRequest
{
    protected $models;

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

    public function withText($text)
    {
        return App::make('searchable.engine')->suggest($this->models, $text);
    }
}
