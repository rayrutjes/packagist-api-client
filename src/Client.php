<?php

/*
 * This file is part of PackagistApi library.
 *
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PackagistApi;

use PackagistApi\Adapter\AdapterInterface;

final class Client
{
    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     * @param string           $endpoint
     */
    public function __construct(AdapterInterface $adapter, $endpoint = 'https://packagist.org')
    {
        $this->adapter = $adapter;
        $this->endpoint = $endpoint;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param array $parameters The query string parameters:
     *                          - vendor: Filter by organization (e.g., 'composer')
     *                          - type: Filter by type (e.g., 'composer-plugin')
     *
     * @return array
     */
    public function getAllPackageNames(array $parameters = [])
    {
        $url = sprintf('%s/packages/list.json', $this->endpoint);

        return $this->request($url, $parameters);
    }

    /**
     * @param string $packageName
     *
     * @return array
     */
    public function getPackageByName($packageName)
    {
        $url = sprintf('%s/packages/%s.json', $this->endpoint, $packageName);

        return $this->request($url);
    }

    /**
     * @param array $parameters The query string parameters:
     *                          - q: Filter by name (e.g., 'monolog')
     *                          - tags: Filter by tag (e.g., 'psr-3')
     *                          - type: Filter by type (e.g., 'symfony-bundle')
     *                          - per_page: Change the pagination step (e.g., 10)
     *                          - page: The results page (e.g., 3)
     *
     * @return array
     */
    public function searchPackages(array $parameters = [])
    {
        $url = sprintf('%s/search.json', $this->endpoint);

        return $this->request($url, $parameters);
    }

    /**
     * @param array $parameters The query string parameters:
     *                          - per_page: Change the pagination step (e.g., 10)
     *                          - page: The results page (e.g., 3)
     *
     * @return array
     */
    public function getPopularPackages(array $parameters = [])
    {
        $url = sprintf('%s/explore/popular.json', $this->endpoint);

        return $this->request($url, $parameters);
    }

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return array
     */
    private function request($url, array $parameters = [])
    {
        if (!empty($parameters)) {
            $url .= '?'.http_build_query($parameters);
        }

        $result = $this->adapter->get($url);

        return json_decode($result, true);
    }
}
