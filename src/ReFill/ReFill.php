<?php namespace ReFill;

use Predis\Client;

class ReFill
{

    protected $redis;

    public function __construct(Client $connection)
    {

        $this->redis = $connection;
    }

    public function cache($key, ReFillCollection $collection, $expire = null)
    {
        foreach($collection as $data) {
            $this->redis->hset('refill-data:' . $key, $data->uniqueId, (string) $data);
        }
    }

    public function match($fragment)
    {
        // TODO: write logic here
    }
}
