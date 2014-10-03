<?php namespace ReFill;


use RuntimeException;

class JsonEncoded
{

    protected $messages = [
        JSON_ERROR_NONE           => 'No error has occurred',
        JSON_ERROR_DEPTH          => 'The maximum stack depth has been exceeded',
        JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
        JSON_ERROR_CTRL_CHAR      => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX         => 'Syntax error',
        JSON_ERROR_UTF8           => 'Malformed UTF-8 characters, possibly incorrectly encoded'
    ];

    protected $data;

    public function __construct($data)
    {

        $this->data = $data;
    }

    public function encoded($options = 0)
    {

        $result = json_encode($this->data, $options);

        if ($result) {
            return $result;
        }

        throw new RuntimeException($this->messages[json_last_error()]);
    }

    public function decoded($assoc = false)
    {

        $result = json_decode($this->data, $assoc);

        if ($result) {
            return $result;
        }

        throw new RuntimeException($this->messages[json_last_error()]);
    }
}
