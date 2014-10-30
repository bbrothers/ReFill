<?php

use Mockery as m;
use ReFill\ReFill;
use ReFill\ReFillCollection;

class ReFillTest extends PHPUnit_Framework_TestCase
{

    private $refill;

    public function setUp()
    {
        $client = m::mock('Predis\Client');

        $client->shouldReceive('getOptions');
        $client->shouldReceive('hset');
        $client->shouldReceive('smembers');
        $client->shouldReceive('sadd');
        $client->shouldReceive('zAdd');

        $semantic = new \ReFill\ReFillSemantic;

        $this->refill = new ReFill($client, $semantic);

    }

    public function test_it_should_catalog_a_collection()
    {
        $faker = Faker\Factory::create();

        $list = [];

        for ($i = 0; $i < 100; $i++) {
            $list[] = ['id' => $i, 'name' => $faker->name];
        }

        $this->refill->catalog('names', ReFillCollection::fromArray($list));
    }



    public function tearDown()
    {
        m::close();
    }
}
