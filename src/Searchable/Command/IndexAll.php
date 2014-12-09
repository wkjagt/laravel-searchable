<?php namespace Searchable\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * An artisan command to index all instances of a model.
 *
 * Class IndexAll
 * @package Searchable\Command
 */
class IndexAll extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'searchable:indexall';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Builds a complete Searchable index for an Eloquent model';

    protected function getArguments()
    {
        return [['modelclass', InputArgument::REQUIRED, 'The model class to index']];
    }

    /**
     * {@inheritdoc}
     */
    public function fire()
    {
        $modelClass = str_replace('/', '\\', $this->argument('modelclass'));

        $model = $this->laravel->make($modelClass);

        $model->createMapping();

        $this->indexModel($model);
    }

    /**
     * @param $model
     * @param $modelClass
     */
    protected function indexModel($model)
    {
        $modelClass = get_class($model);

        if( ! in_array('Searchable\\SearchableTrait', class_uses($modelClass))) {

            return $this->error(sprintf('Model [%s] doesn\'t use Searchable\\SearchableTrait', $modelClass));
        }

        $this->info(sprintf('Indexing %d models of type [%s] to index [%s] using document type [%s]',
            $model->count(), $modelClass, $modelClass::getSearchIndexName(), $modelClass::getSearchDocumentType()));

        $model->indexAll();
    }

} 