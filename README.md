# Track pageviews in your Laravel app

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nickdekruijk/pageviews.svg?style=flat-square)](https://packagist.org/packages/nickdekruijk/pageviews)
[![Build Status](https://img.shields.io/travis/nickdekruijk/pageviews/master.svg?style=flat-square)](https://travis-ci.org/nickdekruijk/pageviews)
[![Quality Score](https://img.shields.io/scrutinizer/g/nickdekruijk/pageviews.svg?style=flat-square)](https://scrutinizer-ci.com/g/nickdekruijk/pageviews)
[![Total Downloads](https://img.shields.io/packagist/dt/nickdekruijk/pageviews.svg?style=flat-square)](https://packagist.org/packages/nickdekruijk/pageviews)

A simple pageviews counter/tracker for your Laravel Application. Still in early development.
It will anonymize visitor ip addresses by default to respect privacy laws.

## Installation

You can install the package via composer:

```bash
composer require nickdekruijk/pageviews
```

## Getting started
If you don't like the default configuration edit `config/pageviews.php` after publishing the config file with:
```bash
php artisan vendor:publish --tag=config --provider="NickDeKruijk\Pageviews\PageviewsServiceProvider"
```
Then run the migration
```bash
php artisan migrate
```

### Security

If you discover any security related issues, please email git@nickdekruijk.nl instead of using the issue tracker.

## Credits

- [Nick de Kruijk](https://github.com/nickdekruijk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
