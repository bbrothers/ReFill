<?php

use ReFill\JsonEncoded;

class JsonEncodedTest extends PHPUnit_Framework_TestCase
{

    public function test_it_should_return_a_json_encoded_object()
    {

        $array = ['test' => 'string to be encoded'];

        $json = new JsonEncoded($array);

        $this->assertEquals(json_encode($array), $json->encoded());
    }

    public function test_it_should_decode_a_json_encoded_object()
    {

        $array = json_encode(['test' => 'string to be encoded']);

        $json = new JsonEncoded($array);

        $this->assertEquals(json_decode($array), $json->decoded());
    }
}
