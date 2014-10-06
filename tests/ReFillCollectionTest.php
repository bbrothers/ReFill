<?php

use ReFill\ReFillCollection;

class ReFillCollectionTest extends PHPUnit_Framework_TestCase
{

    public function test_creating_collection_from_an_assoc_array()
    {

        $this->assertInstanceOf(
            'ReFill\ReFillCollection',
            ReFillCollection::fromArray([
                [
                    'id' => 1,
                    'name' => 'test'
                ], [
                    'id' => 2,
                    'name' => 'test2'
                ],
            ])
        );
    }

    public function test_creating_collection_from_a_key_value_array()
    {

        $this->assertInstanceOf(
            'ReFill\ReFillCollection',
            ReFillCollection::fromKeyValue([
                1 => 'test',
                2 => 'test2'
            ])
        );
    }

    public function test_creating_collection_from_an_object()
    {

        $objects = [];
        for ($i = 0; $i < 20; $i++) {
            $object = new stdClass;
            $object->id = $i;
            $object->name = 'test' . $i;
            $objects[] = $object;
        }

        $this->assertInstanceOf(
            'ReFill\ReFillCollection',
            ReFillCollection::fromObject($objects)
        );
    }
}
