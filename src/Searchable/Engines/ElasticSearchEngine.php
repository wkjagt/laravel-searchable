<?php

namespace Searchable\Engines;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Database\Eloquent\Model;
use Elasticsearch\Client as ESClient;
use Illuminate\Config\Repository as Config;

/**
 * The Elastic Search search engine.
 *
 * Class ElasticSearchEngine
 * @package Searchable\Engines
 */
class ElasticSearchEngine implements SearchEngineInterface
{
    /**
     * The client to connect to ElasticSearch
     * @var ESClient
     */
    protected $client;

    /**
     * The hydrator object that is used to hydrate search results into
     * Laravel models.
     *
     * @var ElasticSearchSearchResultHydrator
     */
    private $hydrator;

    /**
     * Constructor.
     *
     * @param Config $config
     * @param ElasticSearchSearchResultHydrator $hydrator
     */
    function __construct(Config $config, ElasticSearchSearchResultHydrator $hydrator)
    {
        $clientConfig = [
            'hosts' => $config->get('searchable::elasticsearch.hosts'),
            'logPath' => $config->get('searchable::elasticsearch.logPath'),
            'logLevel' => $config->get('searchable::elasticsearch.logLevel')
        ];

        $this->client = new ESClient($clientConfig);
        $this->hydrator = $hydrator;
    }

    /**
     * Add a model to an Elastic Search index
     *
     * @param Model $model
     */
    public function index(Model $model)
    {
        $params = [
            'index' => $model::getSearchIndexName(),
            'type' => $model::getSearchDocumentType(),
            'body' => $model->toArray(),
            'id' => $model->id
        ];

        $this->client->index($params);
    }

    /**
     * Update a model in its index in Elastic Search
     *
     * @param Model $model
     */
    public function update(Model $model)
    {
        $params = [
            'index' => $model::getSearchIndexName(),
            'type' => $model::getSearchDocumentType(),
            'body' => ['doc' => $model->toArray()],
            'id' => $model->id
        ];

        try {
            $this->client->update($params);

        } catch(Missing404Exception $e) {

            $this->index($model);
        }
    }

    /**
     * Delete a model from an Elastic Search Index
     *
     * @param Model $model
     */
    public function delete(Model $model)
    {
        $params = [
            'index' => $model::getSearchIndexName(),
            'type' => $model::getSearchDocumentType(),
            'id' => $model->id
        ];

        try {
            $this->client->delete($params);

        } catch(Missing404Exception $e) { /* can't delete it if it ain't there */ }
    }

    /**
     * Perform a search for a list of models. The indexes and document types
     * to search are specified by the model.
     *
     * @param array $models
     * @param array $query
     * @return array
     */
    public function search(array $models, array $query)
    {
        $params = [
            'index' => $this->getIndexes($models),
            'type'  => $this->getTypes($models),
            'body' => ['query' => $query]
        ];

        try {
            $queryResponse = $this->client->search($params);

        } catch(Missing404Exception $e) {

            // the index doesn't exist: no results
            return [];
        }

        return $this->hydrator->hydrate($queryResponse, $models);
    }

    /**
     * Get the list of indexes to search for a given list of fully qualified
     * class names of models
     *
     * @param array $models
     * @return array
     */
    protected function getIndexes(array $models)
    {
        $indexes = [];

        foreach($models as $model) {

            $indexes[] = $model::getSearchIndexName();
        }
        return array_unique($indexes);
    }

    /**
     * Get the list of document types to search for a given list of fully qualified
     * class names of models
     *
     * @param array $models
     * @return array
     */
    protected function getTypes(array $models)
    {
        $types = [];

        foreach($models as $model) {

            $types[] = $model::getSearchDocumentType();
        }
        return array_unique($types);
    }
} 