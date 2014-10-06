<?php namespace ReFill;

use Predis\Client;

class ReFill
{

    protected $redis;
    protected $semantic;
    private $namespace;

    public function __construct(Client $connection)
    {

        $this->redis = $connection;

        $this->semantic = new Semantic;

        $this->setNamespace($this->redis->getOptions());
    }

    public function setMinWordLength($minWordLength)
    {

        $this->semantic->setMinWordLength($minWordLength);
    }

    public function invalidate($key, ReFillCollection $collection)
    {
        foreach($collection as $element) {

            $fragments = $this->getFromDictionary($key, $element);

            foreach ($fragments as $fragment) {
                $this->removeFromIndex($key, $fragment, $element);
            }

        }
    }

    public function catalog($key, ReFillCollection $collection)
    {
        if (! is_string($key)) {
            throw new \InvalidArgumentException('Index key must be a string.');
        }

        foreach($collection as $element) {
            $this->addElement($key, $element);
            $this->saveIndex($key, $element);
        }
    }

    protected function saveIndex($key, ReFillable $element)
    {
        foreach($element->words as $word) {
            $fragments = $this->getWordFragments($element, $word);
            foreach ($fragments as $index => $fragment) {
                $this->addToIndex($key, $element, $fragment, $index);
                $this->addToDictionary($key, $element, $index, $fragment);
            }
        }
    }

    public function match($key, $fragment, $limit = -1)
    {
        $uniqueIds = $this->findFragment($key, $this->semantic->sanitize($fragment), $limit);

        if(! empty($uniqueIds)) {
            $list = $this->fetchElement($key, $uniqueIds);

            return $this->formatResponse($list);
        }
        return [];
    }

    protected function findFragment($key, $fragment, $limit = -1)
    {

        return $this->fetchMatchesFromIndex($key, $fragment, $limit);
    }

    protected function formatResponse($list)
    {
        $results = [];
        foreach($list as $element) {
            $results[] = json_decode($element);
        }
        return $results;
    }

    /**
     * @param ReFillable $element
     * @param            $word
     * @return array
     */
    protected function getWordFragments(ReFillable $element, $word)
    {

        if ( ! $fragments = $this->redis->smembers($this->namespace . ':lookup:' . $word)) {
            $fragments = $this->semantic->buildIndex($word);
            $this->redis->sadd($this->namespace . ':lookup:' . $word, $fragments);
            return $fragments;
        }
        return $fragments;
    }

    protected function setNamespace($options)
    {
        $this->namespace = isset($options->prefix) ? $options->prefix->getPrefix() : 'ReFill';
    }

    /**
     * @param $key
     * @param $element
     * @return array
     */
    protected function getFromDictionary($key, $element)
    {

        $fragments = $this->redis->smembers($this->namespace . ':dictionary:' . $key . ':' . $element->hash);
        return $fragments;
    }

    /**
     * @param $key
     * @param $fragment
     * @param $element
     */
    protected function removeFromIndex($key, $fragment, $element)
    {

        $this->redis->zrem($this->namespace . ':index:' . $key . ':' . $fragment, $element->hash);
    }

    /**
     * Saves a json encoded object with an md5 has as it's reference key
     *
     * @param $key
     * @param $element
     */
    protected function addElement($key, $element)
    {

        $this->redis->hset($this->namespace . ':data:' . $key, $element->hash, (string) $element);
    }

    /**
     * Saves a reference (hash) to an object that contains the given word fragment
     *
     *
     * @param            $key
     * @param ReFillable $element
     * @param            $fragment
     * @param            $index
     */
    protected function addToIndex($key, ReFillable $element, $fragment, $index)
    {

        $this->redis->zAdd($this->namespace . ':index:' . $key . ':' . $fragment, $index, $element->hash);
    }

    /**
     * Saves a list of fragments that reference the given hash for revers lookup
     *
     * @param            $key
     * @param ReFillable $element
     * @param            $index
     * @param            $fragment
     */
    protected function addToDictionary($key, ReFillable $element, $index, $fragment)
    {

        $this->redis->zAdd($this->namespace . ':dictionary:' . $key . ':' . $element->hash, $index, $fragment);
    }

    /**
     * @param $key
     * @param $uniqueIds
     * @return array
     */
    protected function fetchElement($key, $uniqueIds)
    {

        $list = $this->redis->hmget($this->namespace . ':data:' . $key, $uniqueIds);
        return $list;
    }

    /**
     * @param $key
     * @param $fragment
     * @param $limit
     * @return array
     */
    protected function fetchMatchesFromIndex($key, $fragment, $limit)
    {

        return $this->redis->zRange(
            $this->namespace . ':index:' . $key . ':' . $fragment,
            strlen($fragment),
            $limit
        );
    }
}
