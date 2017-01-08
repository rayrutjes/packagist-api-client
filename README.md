PackagistApi Client
===================

Let's consume the [Packagist API](https://packagist.org/apidoc).

Installation
------------

This library can be found on [Packagist](https://packagist.org/packages/rayrutjes/packagist-api-client).
The recommended way to install this is through [composer](http://getcomposer.org).

Run these commands to install composer, the library and its dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require rayrutjes/packagist-api-client
```

You then need to install **one** of the following:
```bash
$ php composer.phar require guzzlehttp/guzzle:~5.0
$ php composer.phar require guzzlehttp/guzzle:~6.0
```

Usage
-----

### Initialize client

```php
<?php

require 'vendor/autoload.php';

use PackagistApi\Client;
use PackagistApi\Adapter\GuzzleHttpAdapter;

// Create an adapter.
$adapter = new GuzzleHttpAdapter();

// Create a Client object with the previous adapter.
$packagist = new Client($adapter);

// ...
```

### Returns all package names

Accepts the following optional parameters:
- vendor
- type

```php
<?php
// ... initialize Client like explained above.

$packageNames = $packagist->getAllPackageNames([
    'vendor' => 'composer', // Optional.
    'type'   => 'composer-plugin', // Optional.
]);
var_dump($packageNames);
```

### Get package with all its download statistics

With throw an `PackagistApi\Exception\HttpException` if the package does not exist.

```php
<?php
// ... initialize Client like explained above.

$package = $packagist->getPackageByName('rayrutjes/packagist-api-client');
var_dump($package);
```

### Search for packages

Accepts the following optional parameters:
- q
- tags
- type
- per_page
- page

```php
<?php
// ... initialize Client like explained above.

$packages = $packagist->searchPackages([
    'q'        => 'monolog', // Optionally filter by name.
    'tags'     => 'psr-3', // Optionally filter by tag.
    'type'     => 'symfony-bundle', // Optionally filter by type.
    'per_page' => 10, // Optionally change the number of results per page.
    'page'     => 3, // Optionally choose the results page.
]);
var_dump($packages);

// Iterate over all search results.
$packages = [];
$page = 1;
$limit = 500;
do {
    $result = $packagist->searchPackages([
        'q'        => 'monolog', // Optionally filter by name.
        'per_page' => 100, // Optionally change the number of results per page.
        'page'     => $page, // Optionally choose the results page.
    ]);

    $packages = array_merge($packages, $result['results']);
    ++$page;
} while (isset($result['next']) && count($packages) <= $limit);
var_dump($packages);
```

### Get popular packages

Accepts the following optional parameters:
- per_page
- page

```php
<?php
// ... initialize Client like explained above.

$popularPackages = $packagist->getPopularPackages([
    'per_page' => 10, // Optionally change the number of results per page.
    'page'     => 3, // Optionally choose the results page.
]);
var_dump($popularPackages);

// Iterate over all popular packages.
$packages = [];
$page = 1;
$limit = 500;
do {
    $result = $packagist->getPopularPackages([
        'per_page' => 100, // Optionally change the number of results per page.
        'page'     => $page, // Optionally choose the results page.
    ]);

    $packages = array_merge($packages, $result['packages']);
    ++$page;
} while (isset($result['next']) && count($packages) <= $limit);
var_dump($packages);

```

Contributing
------------

Please see [CONTRIBUTING](https://github.com/rayrutjes/packagist-api-client/blob/master/CONTRIBUTING.md) for details.

Changelog
---------

Please see [CHANGELOG](https://github.com/rayrutjes/packagist-api-client/blob/master/CHANGELOG.md) for details.

Support
-------

[Please open an issue in github](https://github.com/rayrutjes/packagist-api-client/issues)

Contributor Code of Conduct
---------------------------

As contributors and maintainers of this project, we pledge to respect all people
who contribute through reporting issues, posting feature requests, updating
documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free
experience for everyone, regardless of level of experience, gender, gender
identity and expression, sexual orientation, disability, personal appearance,
body size, race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual
language or imagery, derogatory comments or personal attacks, trolling, public
or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject
comments, commits, code, wiki edits, issues, and other contributions that are
not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by opening an issue or contacting one or more of the project
maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/).

License
-------

PackagistApi is released under the MIT License. See the bundled
[LICENSE](https://github.com/rayrutjes/packagist-api-client/blob/master/LICENSE) file for details.
