<?php

namespace Searchable;

use Illuminate\Support\Facades\Facade;

/**
 * Facade definition for Searchable
 *
 * Class SearchableFacade
 * @package Searchable
 */
class SearchableFacade extends Facade
{
    /**
     * Get facade accessor.
     *
     * @return string the name of the service to get from the IoC container
     */
    protected static function getFacadeAccessor()
    {
        return 'searchable';
    }
} 