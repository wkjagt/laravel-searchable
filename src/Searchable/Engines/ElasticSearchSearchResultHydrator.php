<?php

namespace Searchable\Engines;

use Illuminate\Foundation\Application;
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
     * @var Application
     */
    private $app;

    /**
     * Constructor
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

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

    /**
     * Identify a model from an index and document type combination and return
     * an instance of it.
     *
     * @param array $hit
     * @param array $models
     * @return mixed
     */
    protected function getModelFromHit(array $hit, array $models)
    {
        foreach($models as $model) {

            if($model::getSearchIndexName() === $hit['_index'] &&
               $model::getSearchDocumentType() === $hit['_type']
            ) {

                return $this->app->make($model);
            }
        }
    }
}