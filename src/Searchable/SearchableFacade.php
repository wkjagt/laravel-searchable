<?php

namespace Searchable;

use Illuminate\Support\Facades\Facade;

class SearchableFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'searchable';
    }
} 