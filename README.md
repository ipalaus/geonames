Eloquent Geonames
=================

[![Build Status](https://travis-ci.org/ipalaus/geonames.png?branch=master)](https://travis-ci.org/ipalaus/geonames)
[![Coverage Status](https://coveralls.io/repos/ipalaus/geonames/badge.png?branch=master)](https://coveralls.io/r/ipalaus/geonames?branch=master)

A collection of Eloquent models and Commands to get all the power of GeoNames in Laravel.

Installation
------------

Add `ipalaus/geonames` as a requirement to composer.json:

```javascript
{
    "require": {
        "ipalaus/geonames": "3.0.*"
    }
}
```

**Note**: if you're using Laravel 5.1 you can use the version `"ipalaus/geonames": "2.0.*"`.

Update your packages with `composer update` or install with `composer install`.

Once you have installed the dependency, you need to register `geonames` in Laravel. Open your
`app/config/app.php` and add the next provider to your `providers` array:

```php
'Ipalaus\Geonames\GeonamesServiceProvider'
```

If you run now `php artisan` you should see a new namespace **geonames** with a few commands related to the package. In order to proceed with the install, run the next command:

```bash
$ php artisan vendor:publish --provider="Ipalaus\Geonames\GeonamesServiceProvider"
```

This will publish the config file to `config/geonames.php` and the migrations to your `database/migrations` directory. To be able to control what's going on, we recommend you to manually trigger `php artisan migrate`.

Artisan Commands
----------------

### Import

The import command downloads the needed files (configurable in the config file) and run a seeder for each of them. A few options are provided:

 - `--country=XX`: downloads the specific country (ie. US, ES, FR...)
 - `--development`: downloads a smaller names file (~10MB) very useful for development environments.
 - `--fetch-only`: only fetch the files but won't run the seeder. If you want to download the files and then be able to regenerate the tables while offline.
 - `--wipe-files`: forces a delete of the old files.

```bash
$ php artisan geonames:import [--country="..."] [--development] [--fetch-only] [--wipe-files]
```

Importing a production database can take a while. Main file is ~1GB and the seeder has to insert ~6M rows while creating indexes for some fields. In development environments, I highly recommend to use the `--development` option and keep complete imports to production. The final table sizes can also affect your local MySQL instance.

### Install

Publish the package's config and run the needed migrations. You can force the config publishing with the `--force` option.

```bash
$ php artisan geonames:install [-f|--force]
```

### Seed

The is called by the `geonames:import` command. It extends the Laravel's `db:seed` but add to extra options: `path` and `country`. This is due to the size of the files to import and the queries that we have to run, `geonames:import` creates a new `Symfony\Component\Process\Process` for each seeder.

You shouldn't need to call this command directly.

### Truncate

Do you want to truncate the table and start from scratch? Run:

```bash
$ php artisan geonames:truncate
```

Eloquent Models
---------------

### Continent

- Relationships:
 - hasMany `countries`

### Country

- Relationships:
 - belongsTo `continent`
 - hasMany `names`

### Name

- Relationships:
 - belongsTo `country`, eager loaded on every request

... and more to come up!

Integrating to a current Eloquent model
---------------------------------------

Integrating `ipalaus/geonames` with your existing Eloquent models as easy as:

```php
<?php

class User extends Eloquent {

	public function geoname()
	{
		return $this->belongsTo('Ipalaus\Geonames\Eloquent\Name');
	}

}
```

Now you can a `geoname_id` to your `User` model and getting the results with a simple:

```php
$user = User::with('geoname')->find(1);
echo $user->geoname->name;
```

The belongsTo `country` relationship in the `Name` model is always eager loaded. That means that you can get the country name with the same code as above. You just have to echo:

```php
echo $user->geoname->country->name;
```

You can go a step further and eager loaded the `geoname.country.continent` relationship (or whatever existing relation in Geoname models):

```php
$user = User::with('geoname.country.continent')->find(1);
echo $user->geoname->country->continent->name;
```

GeoNames
--------

### Tables reference

I think the original table names are ugly and they can conflict with other tables in a current project. I switched to a less uglier ones. You can check the [migration files](https://github.com/ipalaus/geonames/tree/master/src/migrations) to see the used names for our database schema.

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
