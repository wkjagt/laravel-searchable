<?php

namespace Searchable;

use InvalidArgumentException;

class Searchable
{
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