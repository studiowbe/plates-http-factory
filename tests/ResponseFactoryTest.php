<?php

namespace Studiow\Plates\Test;

use League\Plates\Engine;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Studiow\Plates\ResponseFactory;

class ResponseFactoryTest extends TestCase
{
    public function testCreatingResponse()
    {
        $factory = $this->getResponseFactory('template-content');
        $response = $factory->createResponse('template');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('template-content', $response->getBody()->getContents());
    }

    public function testCreatingResponseWithStatus()
    {
        $factory = $this->getResponseFactory('not found');
        $response = $factory->createResponse('error', [], 404, 'Not Found');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Not Found', $response->getReasonPhrase());
    }

    private function getResponseFactory(string $content, int $code = 200, string $reasonPhrase = ''): ResponseFactory
    {
        $plates = $this->createMock(Engine::class);
        $plates->expects($this->any())
            ->method('render')
            ->willReturn($content);

        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->any())
            ->method('write');
        $body->expects($this->any())
            ->method('getContents')
            ->willReturn($content);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->any())
            ->method('getBody')
            ->willReturn($body);

        $responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $responseFactory->expects($this->once())
            ->method('createResponse')
            ->willReturnCallback(function ($code, $reasonPhrase) use ($response) {
                $response->expects($this->any())
                    ->method('getStatusCode')
                    ->willReturn($code);

                $response->expects($this->any())
                    ->method('getReasonPhrase')
                    ->willReturn($reasonPhrase);

                return $response;
            });

        return new ResponseFactory(
            $plates, $responseFactory
        );
    }
}
