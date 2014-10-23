<?php

namespace spec\Searchable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SearchableObserverSpec extends ObjectBehavior
{
    function let(Application $app)
    {
        $this->beConstructedWith($app);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Searchable\SearchableObserver');
    }
}
