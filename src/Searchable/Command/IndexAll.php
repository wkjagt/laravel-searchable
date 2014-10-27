<?php namespace Searchable\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

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
        $modelClassArg = $this->argument('modelclass');

        $modelClass = str_replace('/', '\\', $modelClassArg);

        $this->laravel->make($modelClass)->indexAll();
    }
} 