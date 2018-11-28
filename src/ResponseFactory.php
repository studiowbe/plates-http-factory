<?php

namespace Studiow\Plates;

use League\Plates\Engine;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactory
{
    private $engine;
    private $responseFactory;

    public function __construct(
        Engine $engine,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->engine = $engine;
        $this->responseFactory = $responseFactory;
    }

    public function getEngine(): Engine
    {
        return $this->engine;
    }

    public function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->responseFactory;
    }

    public function createResponse(string $name, array $data = [], int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($code, $reasonPhrase);

        $response->getBody()->write(
            $this->engine->render($name, $data)
        );

        return $response;
    }
}
