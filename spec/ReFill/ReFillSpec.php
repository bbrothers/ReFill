<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Predis\Client;
use Prophecy\Argument;

class ReFillSpec extends ObjectBehavior
{

    protected $fake;

    function let(Client $client)
    {
        $this->beConstructedWith($client);

    }

    function it_is_initializable($client)
    {
        $this->shouldHaveType('ReFill\ReFill');
    }

    function it_should_catalog_a_list(Client $client)
    {


    }

    function it_should_match_a_word_fragment()
    {

    }
}
