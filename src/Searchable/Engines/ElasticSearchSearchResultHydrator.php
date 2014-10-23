<?php

namespace Searchable\Engines;

use Illuminate\Support\Facades\App;
use Searchable\SearchResult;

/**
 * Class ElasticSearchSearchResultHydrator
 *
 * Parse the response from ElasticSearch and create hydrated instances of the
 * corresponding model.
 *
 * @package Searchable\Engines
 */
class ElasticSearchSearchResultHydrator implements SearchResultHydratorInterface
{
    /**
     * @param array $response
     * @return array
     */
    public function hydrate(array $response, array $models)
    {
        $results = [];

        foreach($response['hits']['hits'] as $hit) {

            $model = $this->getModelFromHit($hit, $models);

            $hydrated = $model->newFromBuilder($hit['_source']);

            $results[] = new SearchResult($hydrated, $hit['_score']);
        }

        return $results;
    }

    protected function getModelFromHit(array $hit, array $models)
    {
        foreach($models as $model) {

            if($model::getSearchIndexName() === $hit['_index'] &&
               $model::getSearchDocumentType() === $hit['_type']
            ) {

                return App::make($model);
            }
        }
    }
}