<?php namespace ReFill;

use Predis\Client;

class ReFill
{

    protected $redis;

    public function __construct(Client $connection)
    {

        $this->redis = $connection;
    }

    public function catalog($key, ReFillCollection $collection)
    {
        foreach($collection as $data) {
            $this->redis->hset('refill-data:' . $key, $data->hash, (string) $data);
            $this->saveIndex($key, $data);
        }
    }

    protected function saveIndex($key, ReFillable $data)
    {
        foreach($data->words as $word) {
            if(! $fragments = $this->redis->smembers($word)) {
                $fragments = $data->buildIndex($word);
                $this->redis->sadd($word, $fragments);
            }
            foreach ($fragments as $index => $fragment) {
                $this->redis->zAdd('refill-index:' . $key . ':' . $fragment, $index, $data->hash);
                $this->redis->zAdd('refill-rev:' . $key . ':' . $data->hash, $index, $fragment);
            }
        }
    }

    public function match($key, $fragment)
    {
        $uniqueIds = $this->findFragment($key, $this->sanitize($fragment));

        if(! empty($uniqueIds)) {
            $list = $this->redis->hmget('refill-data:' . $key, $uniqueIds);

            return $this->formatResponse($list);
        }
        return [];
    }

    protected function findFragment($key, $fragment, $limit = -1)
    {
        return $this->redis->zRange('refill-index:' . $key . ':' . $fragment, strlen($fragment) * -1, $limit);
    }

    private function sanitize($fragment)
    {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9- ]/', '', $fragment)));
    }

    private function formatResponse($list)
    {
        $results = [];
        foreach($list as $data) {
            $results[] = json_decode($data);
        }
        return $results;
    }
}
