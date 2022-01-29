<?php

use Psr\Http\Client\ClientInterface;
use Meng\Soap\HttpBinding\HttpBinding;
use Meng\Soap\HttpBinding\RequestBuilder;
use Meng\Soap\Interpreter;
use Meng\Soap\PSR\SoapClient;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class Factory
{
    public function create(ClientInterface $client, StreamFactoryInterface $streamFactory, RequestFactoryInterface $requestFactory, $wsdl, array $options = [])
    {
        if ($this->isHttpUrl($wsdl)) {
            $request = $requestFactory->createRequest('GET', $wsdl);
            $wsdlResponse = $client->sendRequest($request);
            $wsdl = $wsdlResponse->getBody()->__toString();
            $interpreter = new Interpreter('data://text/plain;base64,' . base64_encode($wsdl), $options);
            $httpBinding = new HttpBinding($interpreter, new RequestBuilder($streamFactory, $requestFactory), $streamFactory);

        } else {
            $httpBinding = new HttpBinding(new Interpreter($wsdl, $options), new RequestBuilder($streamFactory, $requestFactory), $streamFactory);
        }

        return new SoapClient($client, $httpBinding);
    }

    private function isHttpUrl($wsdl)
    {
        return filter_var($wsdl, FILTER_VALIDATE_URL) !== false
            && in_array(parse_url($wsdl, PHP_URL_SCHEME), ['http', 'https']);
    }
}