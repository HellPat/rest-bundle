<?php

declare(strict_types=1);

namespace TerryApi\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;
use TerryApiBundle\Facade\SerializerFacade;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use TerryApiBundle\Event\SerializeEvent;
use TerryApiBundle\ValueObject\HTTPClient;
use TerryApiBundle\ValueObject\HTTPServer;

class SerializerFacadeTest extends TestCase
{
    /**
     * @Mock
     * @var EventDispatcherInterface
     */
    private \Phake_IMock $eventDispatcher;

    /**
     * @Mock
     * @var Request
     */
    private \Phake_IMock $request;

    /**
     * @Mock
     * @var SerializerInterface
     */
    private \Phake_IMock $serializer;

    public function setUp(): void
    {
        parent::setUp();

        \Phake::initAnnotations($this);
        \Phake::when($this->request)->getLocale->thenReturn('en_GB');

        $this->request->headers = new HeaderBag([
            'Accept' => 'application/pdf, application/json, application/xml',
            'Content-Type' => 'application/json'
        ]);
    }

    public function testShouldSerialize()
    {
        $data = [];
        $context = ['ctxkey' => 'ctxValue'];
        $client = HTTPClient::fromRequest($this->request, new HTTPServer());

        $serializeContextEvent = new SerializeEvent($data, $client);
        $serializeContextEvent->mergeToContext($context);

        \Phake::when($this->eventDispatcher)->dispatch->thenReturn($serializeContextEvent);
        \Phake::when($this->serializer)->serialize->thenReturn('[]');

        $serializerFacade = new SerializerFacade($this->eventDispatcher, $this->serializer);
        $serializerFacade->serialize($data, $client);

        \Phake::verify($this->eventDispatcher)->dispatch;
        \Phake::verify($this->serializer)->serialize($data, 'json', $context);
    }
}