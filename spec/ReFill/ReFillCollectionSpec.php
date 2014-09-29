<?php

namespace spec\ReFill;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReFillCollectionSpec extends ObjectBehavior
{

    function it_can_be_created_from_array()
    {
        $array = [
            0 => ['id' => 7, 'name' => 'John Smith'],
            1 => ['id' => 9, 'name' => 'Jane Doe']
        ];
        $this->beConstructedThrough('fromArray', [$array]);

        $this->shouldHaveType('ReFill\ReFillCollection');
    }

    function it_can_be_created_from_key_value_array()
    {
        $array = [
            0 => 'John Smith',
            1 => 'Jane Doe'
        ];
        $this->beConstructedThrough('fromKeyValue', [$array]);

        $this->shouldHaveType('ReFill\ReFillCollection');
    }

    function it_can_be_created_from_object()
    {
        $object = new \stdClass;
        $object->id = 7;
        $object->name = 'John Smith';

        $object2 = new \stdClass;
        $object2->id = 7;
        $object2->name = 'John Smith';

        $array = [$object, $object2];

        $this->beConstructedThrough('fromObject', [$array]);

        $this->shouldHaveType('ReFill\ReFillCollection');
    }

}
