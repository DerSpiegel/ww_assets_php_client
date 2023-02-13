# Change Log

## 3.11.1 - 2023-02-13

Refactoring: Use PHP 8 attributes.

## 3.11.0 - 2023-02-08

Added support for the "promote" API call.

## 3.10.0 - 2023-02-06

Added first PHPUnit tests.
Added first documentation, powered by Sphinx.  

## 3.9.0 - 2022-12-28 

Required PHP 8.1. 
Added support for the "undo checkout" API call. 
Added Postman configuration for playing with the Assets REST API.

## 3.8.0 - 2022-11-30

Added logging of request duration.
Allowed HEAD requests.

## 3.7.0 - 2022-09-23

Added helpers AssetsUtils::getQueryTemplate(), AssetsUtils::escapeForElasticsearch().

## 3.6.2 - 2022-09-12

Allowed disabling SSL certificate verification.

## 3.6.1 - 2022-08-31

Fixed PHP warning.

## 3.6.0 - 2022-07-05

Prevented logging of binary responses.

## 3.5.1 - 2022-07-01

Improved README.

## 3.5.0 - 2022-06-29

Allowed PHP 8.1.

## 3.4.0 - 2022-02-25

Changed to log HTTP requests and responses in LogLevel::DEBUG.

## 3.3.0 - 2021-07-08

Prevented "… must not be accessed before initialization" errors.

## 3.2.0 - 2021-05-20

Added support for the "browse" call.

## 3.1.0 - 2021-04-16

Updated dependencies to allow PHP 8.0 and Guzzle 7.3.

## 3.0.0 - 2021-03-16

Renamed Elvis to Assets (throughout the whole code, including class names) - you need to update your code accordingly!

## 2.4.0 - 2021-02-24

Allowed PHP 8.0 in composer.json.

## 2.3.0 - 2021-02-10

Added method SearchResponse::getFacets().

## 2.2.1 - 2020-09-22

Fixed broken automatic re-login.

## 2.2.0 - 2020-09-21

Added support for checkout and checkin.
Made ElvisClientBase::downloadFileByPath() protected and renamed it.

## 2.1.0 - 2020-08-18

Added support for "copy asset".
Added support for Elvis URL with no slash appended.

## 2.0.0 - 2020-06-03

Added support for removing relations. 
Made a few backward incompatible API changes.

## 1.0.3 - 2020-05-11

Added custom HTTP User-Agent.
Fixed ElvisUtils::cleanUpUnchangedMetadataFields().
Fixed ElvisClient::removeFromId().

## 1.0.2 - 2020-04-27

Removed Monolog dependency.

## 1.0.1 - 2020-04-27

Improved README.

## 1.0.0 - 2020-04-27

Initial version, currently in production use at the SPIEGEL Gruppe.