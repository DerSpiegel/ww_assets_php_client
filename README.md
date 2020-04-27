# A PHP client for WoodWing Elvis DAM

[WoodWing Elvis](https://www.woodwing.com/en/digital-asset-management-system) is a DAM (Digital Asset Management) system.
This PHP client library uses its [REST API](https://helpcenter.woodwing.com/hc/en-us/sections/360000141063-API-REST).

This is not an official library supplied by the WoodWing vendor. 
It has been developed during the WoodWing Elvis implementation at the German [SPIEGEL Gruppe](https://www.spiegelgruppe.de), 2019-2020.  

## Installation

Use [Composer](https://getcomposer.org/) to add this library your project’s composer.json file:

```
$ composer require der-spiegel/ww-elvis-client
```

## Quick test 

Here’s how to do a quick test without setting up your own project (Docker required):

### Install dependencies using Composer

```
$ docker run --rm --interactive --tty \
  --volume $PWD:/app \
  --volume ${COMPOSER_HOME:-$HOME/.composer}:/tmp \
  composer install
```

### Copy and edit the example script

`$ cp UsageExample.php MyExample.php`

Edit your copy, set the correct URL, username and password.

### Then run your copy

```
$ docker run -it --rm --name ww-elvis-client-example \
  --volume "$PWD":/usr/src/myapp --workdir /usr/src/myapp \
  php:7.4-cli php MyExample.php
```

## Authors

* [Luís Ferreira](https://github.com/lfe-propublish) 
* [Tim Strehle](https://github.com/tistre) - https://twitter.com/tistre

## License

This library is licensed under the MIT License - see the `LICENSE` file for details.
