<?php

namespace Searchable;

use InvalidArgumentException;

/**
 * The access point to most search functionality.
 *
 * @package Searchable
 */
class Searchable
{
    /**
     * Search in multiple models.
     *
     * @param array $models an array of model fully qualified model class names
     * @return SearchRequest
     *
     * @throws InvalidArgumentException if one of the models doesn't use the SearchableTrait
     */
    public function search(array $models)
    {
        $request = new SearchRequest();

        $this->addModels($models, $request);

        return $request;
    }

    public function suggest(array $models)
    {
        $request = new SuggestRequest();

        $this->addModels($models, $request);

        return $request;
    }

    /**
     * @param array $models
     * @param $request
     */
    protected function addModels(array $models, $request)
    {
        foreach ($models as $model) {

            if (!in_array('Searchable\SearchableTrait', class_uses($model))) {

                throw new InvalidArgumentException("class [$model] doesn't use SearchableTrait");
            }

            $request->addModel($model);
        }
    }
} 