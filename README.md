# A PHP client for WoodWing Elvis DAM

[WoodWing Elvis](https://www.woodwing.com/en/digital-asset-management-system) is a DAM (Digital Asset Management) system.
This PHP client library uses its [REST API](https://helpcenter.woodwing.com/hc/en-us/sections/360000141063-API-REST).

This is not an official library supplied by the WoodWing vendor. 
It has been developed during the WoodWing Elvis implementation at the German [SPIEGEL Gruppe](https://www.spiegelgruppe.de), 2019-2020.

## Functionality

* [API login](https://helpcenter.woodwing.com/hc/en-us/articles/115004785283)
* [Search](https://helpcenter.woodwing.com/hc/en-us/articles/115002690386-Elvis-6-REST-API-search): `SearchRequest`
* [Create](https://helpcenter.woodwing.com/hc/en-us/articles/115002690206-Elvis-6-REST-API-create): `CreateRequest`
* [Update](https://helpcenter.woodwing.com/hc/en-us/articles/115002690426-Elvis-6-REST-API-update-check-in): `UpdateRequest`
* [Update bulk](https://helpcenter.woodwing.com/hc/en-us/articles/115002690446-Elvis-6-REST-API-updatebulk): `UpdateBulkRequest`
* [Copy asset](https://helpcenter.woodwing.com/hc/en-us/articles/115002690166-Elvis-6-REST-API-copy): `CopyAssetRequest`
* [Move / rename](https://helpcenter.woodwing.com/hc/en-us/articles/115002690306-Elvis-6-REST-API-move-rename): `MoveRequest`
* [Remove](https://helpcenter.woodwing.com/hc/en-us/articles/115002663483-Elvis-6-REST-API-remove): `RemoveRequest`
* [Create relation](https://helpcenter.woodwing.com/hc/en-us/articles/115002663363-Elvis-6-REST-API-create-relation): `CreateRelationRequest`  
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
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer require der-spiegel/ww-elvis-client monolog/monolog
```

### Copy and edit the example script

`$ cp vendor/der-spiegel/ww-elvis-client/UsageExample.php MyExample.php`

Edit your copy, setting the correct Elvis URL, username (API user preferred) and password in this section:

```php
$elvisConfig = new AssetsConfig(
    'https://elvis.example.com/', // Elvis URL (without app/ or services/ postfix)
    'username',                   // Elvis user name (API user preferred)
    'password'                    // That user's password
);
```

The example script performs a simple search across all Elvis assets (visible for that user)
and returns the first 50 asset IDs – you can leave it as is for a first test:

```php
$elvisClient = new AssetsClient($elvisConfig, $logger); // Create client

$request = (new SearchRequest($elvisConfig))           // Create search request
    ->setQ('')                                         // Elvis query
    ->setMetadataToReturn(['']);                       // Metadata fields to return

$response = $elvisClient->search($request);            // Perform search

foreach ($response->getHits() as $assetResponse) {     // Loop through results
    echo $assetResponse->getId() . "\n";               // Access asset metadata
}
```

### Then run your copy

```
$ docker run -it --rm --name ww-elvis-client-example \
  --volume "$PWD":/usr/src/myapp --workdir /usr/src/myapp \
  php:cli php MyExample.php
```

## Authors

* [Luís Ferreira](https://github.com/lcpaf) 
* [Tim Strehle](https://github.com/tistre) - https://twitter.com/tistre

## License

This library is licensed under the MIT License - see the `LICENSE` file for details.
