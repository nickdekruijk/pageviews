# Track pageviews in your Laravel app

[![Latest Stable Version](https://poser.pugx.org/nickdekruijk/pageviews/v/stable)](https://packagist.org/packages/nickdekruijk/pageviews)
[![Latest Unstable Version](https://poser.pugx.org/nickdekruijk/pageviews/v/unstable)](https://packagist.org/packages/nickdekruijk/pageviews)
[![Monthly Downloads](https://poser.pugx.org/nickdekruijk/pageviews/d/monthly)](https://packagist.org/packages/nickdekruijk/pageviews)
[![Total Downloads](https://poser.pugx.org/nickdekruijk/pageviews/downloads)](https://packagist.org/packages/nickdekruijk/pageviews)
[![License](https://poser.pugx.org/nickdekruijk/pageviews/license)](https://packagist.org/packages/nickdekruijk/pageviews)

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

### Integrate viewing statistics into [nickdekruijk/admin](https://github.com/nickdekruijk/admin)  package
Add this to the modules array in `config/admin.php`:
```php
        'pageviews' => [
            'view' => 'pageviews::admin',
            'icon' => 'fa-area-chart',
        ],
```

### Security

If you discover any security related issues, please email git@nickdekruijk.nl instead of using the issue tracker.

## Credits

- [Nick de Kruijk](https://github.com/nickdekruijk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
