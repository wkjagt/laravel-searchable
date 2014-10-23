<?php

namespace Searchable;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SearchResult
 *
 * Represents one search result from the search engine. The response from
 *
 * @package Searchable
 */
class SearchResult
{
    /**
     * The model object representing the search result.
     *
     * @var Model
     */
    public $hit;

    /**
     * The score of the result (higher = more relevant)
     *
     * @var float
     */
    public $score;

    public function __construct(Model $hit, $score)
    {
        $this->hit = $hit;
        $this->score = $score;
    }
}