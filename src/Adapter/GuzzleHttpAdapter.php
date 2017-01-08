<?php

/*
 * This file is part of PackagistApi library.
 *
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PackagistApi\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use PackagistApi\Exception\HttpException;
use Psr\Http\Message\ResponseInterface;

final class GuzzleHttpAdapter implements AdapterInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @param ClientInterface|null $client
     */
    public function __construct(ClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        try {
            $this->response = $this->client->request('GET', $url);
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->handleError();
        }

        return $this->response->getBody();
    }

    /**
     * @throws HttpException
     */
    private function handleError()
    {
        $body = (string) $this->response->getBody();
        $code = (int) $this->response->getStatusCode();

        $content = json_decode($body);

        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }
}
