Eloquent Geonames
=================

Installation
------------

Add `ipalaus/eloquent-geonames` as a requirement to composer.json:

```javascript
{
    "require": {
        "ipalaus/eloquent-geonames": "dev-master"
    }
}
```

Update your packages with `composer update` or install with `composer install`.

Once you have installed the dependency, you need to register `eloquent-geonames` in Laravel. Open your
`app/config/app.php` and add the next provider to your `providers` array:

```php
'Ipalaus\EloquentGeonames\EloquentGeonamesServiceProvider'
```

If you run now `php artisan` you should see a new namespace **geonames** with a few commands related to the package. In order to proceed with the install, run the next command:

```bash
$ php artisan geonames:install
```

This will publish the config file to `app/config/packages/ipalaus/eloquent-geonames/config.php` and run the migrations for you. Ideally, the package should publish the migrations but we will wait until the current pull request laravel/framework#2649  gets merged.