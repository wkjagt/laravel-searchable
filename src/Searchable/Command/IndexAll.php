<?php namespace Searchable\Command;

use Illuminate\Console\Command;

class IndexAll extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'command.searchable.indexall';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Builds a complete Searchable index for an Eloquent model';
} 