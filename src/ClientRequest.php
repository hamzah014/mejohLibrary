<?php

namespace MejohLibrary;
use Exception;

class ClientRequest
{
    protected $result;

    public function __construct()
    {
    }

    public function build($result)
    {
        $this->result = $result;

        return $this;
    }

    public function getContent()
    {
        return $this->result['content'];
    }

    public function getHeader()
    {
        return $this->result['header'];
    }

    public function getStatusCode()
    {
        return $this->result['code'];
    }

    public function getResponse()
    {
        return $this->result['response'];
    }

}
