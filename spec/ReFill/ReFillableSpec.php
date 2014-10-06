<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use ReFill\JsonEncoded;

class ReFillableSpec extends ObjectBehavior
{
    function let(JsonEncoded $json)
    {
        $uniqueId = 0;
        $string = "John Smith";
        $this->beConstructedWith($uniqueId, $string, $json);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('ReFill\ReFillable');
    }
}
