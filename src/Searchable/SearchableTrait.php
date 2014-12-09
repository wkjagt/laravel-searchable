<?php namespace Searchable;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;

/**
 * Class SearchableTrait
 *
 * Use this trait in any model to make it instantly searchable.
 *
 * @package Searchable
 */
trait SearchableTrait
{
    /**
     * The search engine to use.
     *
     * @var SearchEngineInterface
     */
    protected static $engine;

    /**
     * The boot method of the trait. This method doesn't seem to be well documented
     * but a mention of it can be found here http://laravel.com/docs/4.2/eloquent#global-scopes
     */
    public static function bootSearchableTrait()
    {
        $class = __CLASS__;

        $class::observe(App::make('Searchable\SearchableObserver'));
    }

    public function createMapping()
    {
        App::make('searchable.engine')->createMappingFor($this);
    }

    /**
     * Get the document type to use when saving a model in a search engine. If the implementing
     * model doesn't provide a static searchDocumentType method, the fully qualified class name
     * is used, after replacing backslashes by dots.
     *
     * @return string
     */
    public static function getSearchDocumentType()
    {
        if(property_exists(__CLASS__, 'searchDocumentType') && is_string(static::$searchDocumentType)) {

            return static::$searchDocumentType;
        }

        return str_replace('\\', '.', __CLASS__);
    }

    /**
     * Get the name of the index to use. If the implementing model doesn't provide a static
     * searchDocumentType method, 'main' is used.
     *
     * @return string the name of the index to use.
     */
    public static function getSearchIndexName()
    {
        if(property_exists(__CLASS__, 'searchIndexName') && is_string(static::$searchIndexName)) {

            return static::$searchIndexName;
        }

        return 'default';
    }

    /**
     * Perform a search on the model by providing a query.
     *
     * @param array $query the query used to perform the search.
     * @return array. An array of SearchResult objects.
     *
     * @todo make this less ElasticSeach specific
     */
    public function search(array $query)
    {
        return App::make('searchable')->search([__CLASS__])->withQuery($query);
    }

    /**
     * Index all models of this type
     */
    public function indexAll()
    {
        foreach($this->all() as $model) {

            App::make('searchable.engine')->index($model);
        }
    }
}