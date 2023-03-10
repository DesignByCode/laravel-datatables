# Create Laravel data tables

[![Latest Version on Packagist](https://img.shields.io/packagist/v/designbycode/laravel-datatables.svg?style=flat-square)](https://packagist.org/packages/designbycode/laravel-datatables)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/designbycode/laravel-datatables/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/designbycode/laravel-datatables/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/designbycode/laravel-datatables/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/designbycode/laravel-datatables/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/designbycode/laravel-datatables.svg?style=flat-square)](https://packagist.org/packages/designbycode/laravel-datatables)

This package is the backend implementation for creating datatables for Laravel modal.



## Installation

You can install the package via composer:

```bash
composer require designbycode/laravel-datatables
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-datatables-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-datatables-views"
```

## Usage

```php

use Designbycode\Datatables\DataTableController;

class CategoriesDataTable extends DataTableController
{
    public function builder(): Builder
    {
        return Category::query();
    }
    
}
```

## Use with InertiaJs


```php 

use Designbycode\Datatables\DataTableController;
use Inertia\Inertia;
use Inertia\Response;

class CategoriesDataTable extends DataTableController
{
    public function builder(): Builder
    {
        return Category::query();
    }
    
    public function index(): Response
    {
        return Inertia::render('Categories', )
    }
    
}


```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Claude Myburgh](https://github.com/designbycode)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
