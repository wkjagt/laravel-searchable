<?php

namespace spec\Searchable;

use Illuminate\Database\Eloquent\Model;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SearchResultSpec extends ObjectBehavior
{
    function let(Model $model)
    {
        $this->beConstructedWith($model, 1234);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Searchable\SearchResult');
    }

    function it_sets_the_hit_as_a_public_property(Model $model)
    {
        $this->hit->shouldBe($model);
    }

    function it_sets_the_score_as_a_public_property()
    {
        $this->score->shouldBe(1234);
    }
}
