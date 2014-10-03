<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReFillableSpec extends ObjectBehavior
{
    function let()
    {
        $uniqueId = 0;
        $string = "John Smith";
        $this->beConstructedWith($uniqueId, $string);
    }
    function it_is_initializable()
    {
        $this->shouldHaveType('ReFill\ReFillable');
    }
}
