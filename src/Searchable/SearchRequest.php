<?php

namespace Searchable;

use Illuminate\Support\Facades\App;

class SearchRequest
{
    protected $indexes = [];

    protected $docTypes = [];

    protected $models = [];

    public function addModel($model)
    {
        $this->models[] = $model;
    }

    public function withQuery(array $query)
    {
        return App::make('searchable.engine')->search($this->models, $query);
    }
} 