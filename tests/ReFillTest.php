<?php

use Mockery as m;
use ReFill\ReFill;

class ReFillTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $client = m::mock('Predis\Client');

        $client->shouldReceive('getOptions');

        $this->refill = new ReFill($client);

    }

    public function test_it_should_catalog_a_collection()
    {
        return true;
    }

    public function tearDown()
    {
        m::close();
    }
}
