<?php namespace ReFill;

use Predis\Client;

class ReFill
{

    protected $redis;

    public function __construct(Client $connection)
    {

        $this->redis = $connection;
    }

    public function cache($key, ReFillCollection $collection)
    {
        foreach($collection as $data) {
            $this->redis->hset('refill-data:' . $key, $data->uniqueId, (string) $data);
            $this->saveIndex($key, $data->uniqueId, $data->index);
        }
    }

    protected function saveIndex($key, $uniqueId, array $fragments)
    {
        foreach ($fragments as $index => $fragment) {
            $this->redis->zAdd('refill-index:' . $key . ':' . $fragment, $index, $uniqueId);
        }
    }

    public function match($key, $fragment)
    {
        $uniqueIds = $this->findFragment($key, $fragment);
        $list = $this->redis->hmget('refill-data:' . $key, implode(', ', $uniqueIds));
        return $list;
    }

    protected function findFragment($key, $fragment, $limit = -1)
    {
        return $this->redis->zRange('refill-index:' . $key . ':' . $fragment, strlen($fragment) * -1, $limit);
    }
}
