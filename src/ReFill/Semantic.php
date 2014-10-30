<?php

namespace ReFill;

interface Semantic
{

    public function sanitize($string);

    public function buildIndex($word);

    public function splitWords($string);

    public function discardInvalidWords($words);

    public function getValidWords($string);

    /**
     * @return int
     */
    public function getMinWordLength();

    /**
     * @param int $minWordLength
     */
    public function setMinWordLength($minWordLength);
}
