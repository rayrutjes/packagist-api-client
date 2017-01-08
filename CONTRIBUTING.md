CONTRIBUTING
============

Contributions are **welcome** and be fully **credited** <3

Before submitting any pull request please make sure that the coding standards are respected and that all the unit tests are passing.

Coding Standard
---------------
This library will use the [Symfony2 Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html).

These conventions are enforced using the [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) tool. PHP-CS-Fixer is installed as a dev dependency and will therefore be available after running `composer install` or `composer update`.

```bash
$ cd /path/to/PackagistApi
$ ./vendor/bin/php-cs-fixer fix
```


PHPUnit tests
-------------

Install composer dependencies and run the tests:

```bash
$ composer install
$ vendor/bin/phpunit
```

**Happy coding** !
