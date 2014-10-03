<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Predis\Client;
use Prophecy\Argument;
use ReFill\ReFillCollection;

class ReFillSpec extends ObjectBehavior
{

    protected $fake;

    function let(Client $client)
    {
        $this->beConstructedWith($client);
        $client->hset('key', 'field', '$names')->shouldBeCalled();

    }

    function it_is_initializable($client)
    {
        $this->shouldHaveType('ReFill\ReFill');
    }

    function it_should_catalog_a_list(Client $client)
    {

        $names = [
            0 => 'John Doe',
            1 => 'Jane Doe',
            2 => 'Bob Smith'
        ];

//        $this->beConstructedWith($client);


        $this->catalog('names', ReFillCollection::fromKeyValue($names));

    }

    function it_should_match_a_word_fragment()
    {

    }
}
