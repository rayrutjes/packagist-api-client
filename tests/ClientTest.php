<?php

/*
 * This file is part of PackagistApi library.
 *
 * (c) Raymond Rutjes <raymond.rutjes@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PackagistApi\Tests;

use PackagistApi\Client;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $adapter;

    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();
        $this->adapter = $this->getAdapterMock();
        $this->client = new Client($this->adapter);
    }

    public function testAdapterCanBeRetrieved()
    {
        $this->assertEquals($this->adapter, $this->client->getAdapter());
    }

    public function testDefaultEndpointIsPackagistOrg()
    {
        $this->assertEquals('https://packagist.org', $this->client->getEndpoint());
    }

    public function testDefaultEndpointCanBeChanged()
    {
        $adapter = $this->getAdapterMock();
        $client = new Client($adapter, 'localhost');
        $this->assertEquals('localhost', $client->getEndpoint());
    }

    public function testCanGetAllPackageNames()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/packages/list.json')
            ->willReturn('
                {
                    "packageNames":[
                        "author\/package-name",
                        "author2\/package-name2"
                    ]
                }
            ');

        $actual = $this->client->getAllPackageNames();
        $expected = [
            'packageNames' => [
                'author/package-name',
                'author2/package-name2',
            ],
        ];
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetAllPackageNamesFilteredByVendor()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/packages/list.json?vendor=test')
            ->willReturn('
                {
                    "packageNames":[]
                }
            ');

        $actual = $this->client->getAllPackageNames(['vendor' => 'test']);
        $expected = ['packageNames' => []];
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetAllPackageNamesFilteredByType()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/packages/list.json?type=test')
            ->willReturn('
                {
                    "packageNames":[]
                }
            ');

        $actual = $this->client->getAllPackageNames(['type' => 'test']);
        $expected = ['packageNames' => []];
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetAllPackageNamesFilteredByVendorAndType()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/packages/list.json?vendor=test1&type=test2')
            ->willReturn('
                {
                    "packageNames":[]
                }
            ');

        $actual = $this->client->getAllPackageNames(['vendor' => 'test1', 'type' => 'test2']);
        $expected = ['packageNames' => []];
        $this->assertEquals($expected, $actual);
    }

    public function testCanGetPackageByItsName()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/packages/author/package-name.json')
            ->willReturn('
                {
                    "package": {
                        "name": "author\/package-name",
                        "description": "Package description",
                        "time": "2013-12-16T20:28:57+00:00",
                        "maintainers": [
                            {
                                "name": "author"
                            }
                        ],
                        "versions": {
                            "1.0.0": {}
                        },
                        "type": "library",
                        "repository": "https:\/\/github.com\/author\/package-name",
                        "github_stars": 1,
                        "github_watchers": 2,
                        "github_forks": 3,
                        "github_open_issues": 4,
                        "language": "PHP",
                        "dependents": 5,
                        "suggesters": 6,
                        "downloads": {
                            "total": 7,
                            "monthly": 8,
                            "daily": 9
                        },
                        "favers": 10
                    }
                }
            ');

        $actual = $this->client->getPackageByName('author/package-name');
        $expected = [
            'package' => [
                    'name' => 'author/package-name',
                    'description' => 'Package description',
                    'time' => '2013-12-16T20:28:57+00:00',
                    'maintainers' => [
                        [
                            'name' => 'author',
                        ],
                    ],
                    'versions' => [
                        '1.0.0' => [
                        ],
                    ],
                    'type' => 'library',
                    'repository' => 'https://github.com/author/package-name',
                    'github_stars' => 1,
                    'github_watchers' => 2,
                    'github_forks' => 3,
                    'github_open_issues' => 4,
                    'language' => 'PHP',
                    'dependents' => 5,
                    'suggesters' => 6,
                    'downloads' => [
                        'total' => 7,
                        'monthly' => 8,
                        'daily' => 9,
                    ],
                    'favers' => 10,
                ],
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testCanSearchForPackages()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/search.json?q=a&tags=b&type=c&per_page=1')
            ->willReturn('
                {
                    "results": [{
                        "name": "author\/package-name",
                        "description": "Package description",
                        "url": "https:\/\/packagist.org\/packages\/author\/package-name",
                        "repository": "https:\/\/github.com\/author\/package-name",
                        "downloads": 1,
                        "favers": 2
                    },
                    {
                        "name": "author2\/package-name2",
                        "description": "Package description 2",
                        "url": "https:\/\/packagist.org\/packages\/author2\/package-name2",
                        "repository": "https:\/\/github.com\/author2\/package-name2",
                        "downloads": 3,
                        "favers": 4
                    }]
                }
            ');

        $actual = $this->client->searchPackages(
            [
                'q' => 'a',
                'tags' => 'b',
                'type' => 'c',
                'per_page' => 1,
            ]
        );

        $expected = [
            'results' => [
                [
                    'name' => 'author/package-name',
                    'description' => 'Package description',
                    'url' => 'https://packagist.org/packages/author/package-name',
                    'repository' => 'https://github.com/author/package-name',
                    'downloads' => 1,
                    'favers' => 2,
                ],
                [
                    'name' => 'author2/package-name2',
                    'description' => 'Package description 2',
                    'url' => 'https://packagist.org/packages/author2/package-name2',
                    'repository' => 'https://github.com/author2/package-name2',
                    'downloads' => 3,
                    'favers' => 4,
                ],
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testCanGetPopularPackages()
    {
        $this->adapter
            ->expects($this->once())
            ->method('get')
            ->with('https://packagist.org/explore/popular.json?per_page=1')
            ->willReturn('
                {
                    "packages": [{
                        "name": "author\/package-name",
                        "description": "Package description",
                        "url": "https:\/\/packagist.org\/packages\/author\/package-name",
                        "downloads": 1,
                        "favers": 2
                    },
                    {
                        "name": "author2\/package-name2",
                        "description": "Package description 2",
                        "url": "https:\/\/packagist.org\/packages\/author2\/package-name2",
                        "downloads": 3,
                        "favers": 4
                    }]
                }
            ');

        $actual = $this->client->getPopularPackages(['per_page' => 1]);

        $expected = [
            'packages' => [
                [
                    'name' => 'author/package-name',
                    'description' => 'Package description',
                    'url' => 'https://packagist.org/packages/author/package-name',
                    'downloads' => 1,
                    'favers' => 2,
                ],
                [
                    'name' => 'author2/package-name2',
                    'description' => 'Package description 2',
                    'url' => 'https://packagist.org/packages/author2/package-name2',
                    'downloads' => 3,
                    'favers' => 4,
                ],
            ],
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getAdapterMock()
    {
        return $this->getMockBuilder('PackagistApi\Adapter\AdapterInterface')->getMock();
    }
}
