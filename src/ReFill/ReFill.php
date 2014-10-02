<?php namespace ReFill;

use Predis\Client;

class ReFill
{

    protected $redis;
    private $namespace;

    public function __construct(Client $connection)
    {

        $this->redis = $connection;

        $this->setNamespace($this->redis->getOptions());
    }

    public function invalidate($key, ReFillCollection $collection)
    {
        foreach($collection as $data) {
            $fragments = $this->redis->smembers($this->namespace . ':dictionary:' . $key . ':' . $data->hash);
            if (empty($fragments)) {
                throw new \InvalidArgumentException;
            }
            foreach ($fragments as $fragment) {
                $this->redis->zrem($this->namespace . ':index:' . $key . ':' . $fragment, $data->hash);
            }
        }
    }

    public function catalog($key, ReFillCollection $collection)
    {
        if (empty($collection)) {
            throw new \InvalidArgumentException;
        }

        foreach($collection as $data) {
            $this->redis->hset($this->namespace . ':data:' . $key, $data->hash, (string) $data);
            $this->saveIndex($key, $data);
        }
    }

    protected function saveIndex($key, ReFillable $data)
    {
        foreach($data->words as $word) {
            $fragments = $this->getWordFragments($data, $word);
            foreach ($fragments as $index => $fragment) {
                $this->redis->zAdd($this->namespace . ':index:' . $key . ':' . $fragment, $index, $data->hash);
                $this->redis->zAdd($this->namespace . ':dictionary:' . $key . ':' . $data->hash, $index, $fragment);
            }
        }
    }

    public function match($key, $fragment)
    {
        $uniqueIds = $this->findFragment($key, $this->sanitize($fragment));

        if(! empty($uniqueIds)) {
            $list = $this->redis->hmget($this->namespace . ':data:' . $key, $uniqueIds);

            return $this->formatResponse($list);
        }
        return [];
    }

    protected function findFragment($key, $fragment, $limit = -1)
    {
        return $this->redis->zRange($this->namespace . ':index:' . $key . ':' . $fragment, strlen($fragment) * -1, $limit);
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

    /**
     * @param ReFillable $data
     * @param            $word
     * @return array
     */
    public function getWordFragments(ReFillable $data, $word)
    {

        if ( ! $fragments = $this->redis->smembers($word)) {
            $fragments = $data->buildIndex($word);
            $this->redis->sadd($word, $fragments);
            return $fragments;
        }
        return $fragments;
    }

    protected function setNamespace($options)
    {
        $this->namespace = isset($options->prefix) ? $options->prefix->getPrefix() : 'ReFill';
    }
}
