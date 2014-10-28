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
     */
    public function search(array $models)
    {
        $request = new SearchRequest();

        foreach($models as $model) {

            if( ! in_array('Searchable\SearchableTrait', class_uses($model))) {

                throw new InvalidArgumentException("class [$model] doesn't use SearchableTrait");
            }

            $request->addModel($model);
        }

        return $request;
    }
} 