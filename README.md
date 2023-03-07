# A PHP client for WoodWing Assets

[WoodWing Assets](https://www.woodwing.com/en/digital-asset-management-system) is a DAM (Digital Asset Management) system.
This PHP client library uses its [REST API](https://helpcenter.woodwing.com/hc/en-us/sections/360008455892-APIs-REST).

This is not an official library supplied by the WoodWing vendor. 
It has been developed during the WoodWing Assets implementation at the German [SPIEGEL Gruppe](https://www.spiegelgruppe.de), 2019-2020.

## Functionality

* [API login](https://helpcenter.woodwing.com/hc/en-us/articles/360041851192-Assets-Server-REST-API-API-login)
* [Search](https://helpcenter.woodwing.com/hc/en-us/articles/360041851432-Assets-Server-REST-API-search): `SearchRequest`
* [Browse](https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse): `BrowseRequest`
* [Create](https://helpcenter.woodwing.com/hc/en-us/articles/360042268771-Assets-Server-REST-API-create): `CreateRequest`
* [Update](https://helpcenter.woodwing.com/hc/en-us/articles/360042268971-Assets-Server-REST-API-update-check-in): `UpdateRequest`
* [Update bulk](https://helpcenter.woodwing.com/hc/en-us/articles/360042268991-Assets-Server-REST-API-updatebulk): `UpdateBulkRequest`
* [Copy asset](https://helpcenter.woodwing.com/hc/en-us/articles/360042268731-Assets-Server-REST-API-copy): `CopyAssetRequest`
* [Move / rename](https://helpcenter.woodwing.com/hc/en-us/articles/360042268891-Assets-Server-REST-API-move-rename): `MoveRequest`
* [Remove](https://helpcenter.woodwing.com/hc/en-us/articles/360041851352-Assets-Server-REST-API-remove): `RemoveRequest`
* [Create relation](https://helpcenter.woodwing.com/hc/en-us/articles/360042268751-Assets-Server-REST-API-create-relation): `CreateRelationRequest`  
* Create folder: `CreateFolderRequest`
* Get folder metadata: `GetFolderRequest`
* Update folder metadata: `UpdateFolderRequest`

## Installation

Use [Composer](https://getcomposer.org/) to add this library your project’s composer.json file:

```
$ composer require der-spiegel/ww-elvis-client
```

## Quick test 

Here’s how to do a quick test, starting from scratch with a new project (Docker required):

### Install dependencies using Composer

```
$ mkdir MyExample && cd MyExample
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer/composer require der-spiegel/ww-elvis-client monolog/monolog
```

### Copy and edit the example script

`$ cp vendor/der-spiegel/ww-elvis-client/UsageExample.php MyExample.php`

Edit your copy, setting the correct Assets URL, username (API user preferred) and password in this section:

```php
$assetsConfig = new AssetsConfig(
    'https://assets.example.com/', // Assets URL (without app/ or services/ postfix)
    'username',                    // Assets user name (API user preferred)
    'password'                     // That user's password
);
```

The example script performs a simple search across all assets (visible for that user)
and returns the first 50 asset IDs – you can leave it as is for a first test:

```php
$assetsClient = new AssetsClient($assetsConfig, $logger); // Create client

$request = (new SearchRequest($assetsConfig))          // Create search request
    ->setQ('')                                         // Assets query
    ->setMetadataToReturn(['']);                       // Metadata fields to return

$response = $assetsClient->search($request);            // Perform search

foreach ($response->getHits() as $assetResponse) {     // Loop through results
    echo $assetResponse->getId() . "\n";               // Access asset metadata
}
```

### Then run your copy

```
$ docker run -it --rm --name assets-client-example \
  --volume "$PWD":/usr/src/myapp --workdir /usr/src/myapp \
  php:cli php MyExample.php
```

## Development

See [Running tests](https://ww-elvis-php-client.readthedocs.io/en/latest/tests.html) for instructions on how to run unit and integration tests.

To regenerate the documentation in docs/_build/html from the source files, run Sphinx:

```
$ docker run --rm --volume "$PWD":/usr/src/myapp --workdir /usr/src/myapp/docs sphinxdoc/sphinx:5.3.0 make html
```

## Authors

* [Tim Strehle](https://github.com/tistre) - https://twitter.com/tistre
* [Luís Ferreira](https://github.com/lcpaf)

## License

This library is licensed under the MIT License - see the `LICENSE` file for details.
