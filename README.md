# laravel-wp-api

[![Build Status](https://travis-ci.org/threesquared/laravel-wp-api.svg?branch=master)](https://travis-ci.org/threesquared/laravel-wp-api) [![Latest Stable Version](https://poser.pugx.org/threesquared/laravel-wp-api/v/stable)](https://packagist.org/packages/threesquared/laravel-wp-api)

Laravel 5 package for the [Wordpress JSON REST API](https://github.com/WP-API/WP-API)

## Install

Simply add the following line to your `composer.json` and run install/update:

    "threesquared/laravel-wp-api": "~2.0"

## Configuration

You will need to add the service provider and optionally the facade alias to your `config/app.php`:

```php
'providers' => array(
  Threesquared\LaravelWpApi\LaravelWpApiServiceProvider::class
)

'aliases' => array(
  'WpApi' => Threesquared\LaravelWpApi\Facades\WpApi::class
),
```

And publish the package config files to configure the location of your Wordpress install:

    php artisan vendor:publish

### Usage

The package provides a simplified interface to some of the existing api methods documented [here](http://wp-api.org/).
You can either use the Facade provided or inject the `Threesquared\LaravelWpApi\WpApi` class.
The amount of items per page can be set as 'per_page' config parameter, a forth parameter of class constructor or as a parameter of class methods.

#### Posts
```php
WpApi::posts($page, $per_page);
```

#### Pages
```php
WpApi::pages($page, $per_page);
```

#### Post
```php
WpApi::post($slug);
```

#### Categories
```php
WpApi::categories();
```

#### Tags
```php
WpApi::tags();
```

#### Category posts
```php
WpApi::categoryPosts($slug, $page, $per_page);
```

#### Author posts
```php
WpApi::authorPosts($slug, $page, $per_page);
```

#### Tag posts
```php
WpApi::tagPosts($slug, $page, $per_page);
```

#### Search
```php
WpApi::search($query, $page, $per_page);
```

#### Archive
```php
WpApi::archive($year, $month, $page, $per_page);
```
