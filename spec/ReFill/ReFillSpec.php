<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Predis\Client;
use Prophecy\Argument;
use ReFill\ReFillable;
use ReFill\ReFillCollection;

class ReFillSpec extends ObjectBehavior
{

    protected $fake;

    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ReFill\ReFill');
    }

    function it_should_catalog_a_list()
    {

        $names = [
            0 => 'John Doe',
            1 => 'Jane Doe',
            2 => 'Bob Smith'
        ];

        $this->catalog('names', ReFillCollection::fromKeyValue($names));

        $this->match('joh')->shouldBe($names[0]);
    }

    function it_should_match_a_word_fragment()
    {

    }
}
