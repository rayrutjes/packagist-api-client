<?php

/*
 * This file is part of PackagistApi library.
 *
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PackagistApi\Tests\Adapter;

use PackagistApi\Adapter\GuzzleHttpAdapter;

class GuzzleHttpAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuzzleHttpAdapter
     */
    private $adapter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = $this->getClientMock();
        $this->adapter = new GuzzleHttpAdapter($this->client);
    }

    public function testCanRetrieveClient()
    {
        $this->assertSame($this->client, $this->adapter->getClient());
    }

    /**
     * @depends testCanRetrieveClient
     */
    public function testShouldProvideDefaultClientIfNoneProvided()
    {
        $adapter = new GuzzleHttpAdapter();
        $this->assertInstanceOf('GuzzleHttp\ClientInterface', $adapter->getClient());
    }

    public function testCanExecuteGetRequest()
    {
        $responseMock = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')->getMock();
        $responseMock->method('getBody')->willReturn('content');

        $this->client
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'https://example.com')
            ->willReturn($responseMock);

        $actual = $this->adapter->get('https://example.com');

        $this->assertSame('content', $actual);
    }

    /**
     * @expectedException \PackagistApi\Exception\HttpException
     * @expectedExceptionCode 400
     * @expectedExceptionMessage The optional packages per_page parameter must be an integer between 1 and 100 (default: 15)
     */
    public function testThrowsHttpExceptionOnGetRequestFailure()
    {
        $errorMessage = 'The optional packages per_page parameter must be an integer between 1 and 100 (default: 15)';
        $errorCode = 400;

        $responseMock = $this->getMockBuilder('Psr\Http\Message\ResponseInterface')->getMock();
        $responseMock->method('getBody')->willReturn('
            {
                "status": "error",
                "message": "'.$errorMessage.'"
            }
        ');
        $responseMock->method('getStatusCode')->willReturn($errorCode);

        $requestExceptionMock = $this->getMockBuilder('GuzzleHttp\Exception\RequestException')
            ->disableOriginalConstructor()
            ->getMock();
        $requestExceptionMock->method('getResponse')->willReturn($responseMock);

        $this->client
            ->method('request')
            ->with('GET', 'https://example.com')
            ->willThrowException($requestExceptionMock);

        $this->adapter->get('https://example.com');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getClientMock()
    {
        return $this->getMockBuilder('GuzzleHttp\ClientInterface')->getMock();
    }
}
