<?php

namespace Meng\Soap\PSR;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Meng\Soap\HttpBinding\HttpBinding;

class SoapClient
{
    private $httpBind;
    private $client;

    public function __construct(ClientInterface $client, HttpBinding $httpBinding)
    {
        $this->client = $client;
        $this->httpBind = $httpBinding;
    }

    public function __call($name, $arguments)
    {
        return $this->call($name, $arguments);
    }

    public function call($name, array $arguments, array $options = null, $inputHeaders = null, array &$outputHeaders = null)
    {
        $request = $this->httpBinding->request($name, $arguments, $options, $inputHeaders);
        try {
            $response = $this->client->sendRequest($request);
            return $this->interpretResponse($this->httpBind, $response, $name, $outputHeaders);
        } finally {
            $request->getBody()->close();
        }
    }

    private function interpretResponse(HttpBinding $httpBinding, ResponseInterface $response, $name, &$outputHeaders)
    {
        try {
            return $httpBinding->response($response, $name, $outputHeaders);
        } finally {
            $response->getBody()->close();
        }
    }
}