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

    protected function saveIndex($key, $uniqueId, array $wordIndex)
    {
        foreach($wordIndex as $word) {
            foreach ($word as $index => $fragment) {
                $this->redis->zAdd('refill-index:' . $key . ':' . $fragment, $index, $uniqueId);
            }
        }
    }

    public function match($key, $fragment)
    {
        $uniqueIds = $this->findFragment($key, $this->sanitize($fragment));
        if(! empty($uniqueIds)) {
            $list = $this->redis->hmget('refill-data:' . $key, $uniqueIds);

            return $this->formatResponse($uniqueIds, $list);
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

    private function formatResponse($uniqueIds, $list)
    {
        $results = [];
        foreach($uniqueIds as $index => $uniqueId) {
            $results[] = [
                'text' => $list[$index],
                'id' => (int) $uniqueId
            ];
        }
        return $results;
    }
}
