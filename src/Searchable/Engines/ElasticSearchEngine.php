<?php

namespace Searchable\Engines;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Database\Eloquent\Model;
use Elasticsearch\Client as ESClient;
use Illuminate\Config\Repository as Config;

class ElasticSearchEngine implements SearchEngineInterface
{
    protected $client;
    /**
     * @var ElasticSearchSearchResultHydrator
     */
    private $hydrator;

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

    protected function getIndexes(array $models)
    {
        $indexes = [];

        foreach($models as $model) {

            $indexes[] = $model::getSearchIndexName();
        }
        return array_unique($indexes);
    }

    protected function getTypes(array $models)
    {
        $types = [];

        foreach($models as $model) {

            $types[] = $model::getSearchDocumentType();
        }
        return array_unique($types);
    }
} 