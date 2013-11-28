Eloquent Geonames
=================

[![Build Status](https://travis-ci.org/ipalaus/eloquent-geonames.png?branch=master)](https://travis-ci.org/ipalaus/eloquent-geonames)

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

Artisan Commands
----------------

### Truncate

Do you want to truncate the table and start from scratch? Run:

```bash
$ php artisan geonames:truncate
```

GeoNames
--------

### Tables reference

I think the original table names are ugly... so I switched to a less uglier ones.

<table>
  <tr>
    <th>Original</th>
    <th>Eloquent Geonames</th>
  </tr>
  <tr>
    <td>geoname</td>
    <td>geonames_names</td>
  </tr>
  <tr>
    <td>alternatename</td>
    <td>geonames_alternate_names</td>
  </tr>
  <tr>
    <td>countryinfo</td>
    <td>geonames_countries</td>
  </tr>
  <tr>
    <td>iso_languagecodes</td>
    <td>geonames_language_codes</td>
  </tr>
  <tr>
    <td>admin1CodesAscii</td>
    <td>geonames_admin_divisions</td>
  </tr>
  <tr>
    <td>admin2Codes</td>
    <td>geonames_admin_subdivisions</td>
  </tr>
  <tr>
    <td>hierarchy</td>
    <td>geonames_hierarchy</td>
  </tr>
  <tr>
    <td>featureCodes</td>
    <td>geonames_features</td>
  </tr>
  <tr>
    <td>timeZones</td>
    <td>geonames_timezones</td>
  </tr>
  <tr>
    <td>continentCodes</td>
    <td>geonames_continents</td>
  </tr>


</table>
