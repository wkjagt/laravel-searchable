<?php namespace Searchable\Engines;

interface SearchResultHydratorInterface
{
    public function hydrate(array $response, array $models);
}