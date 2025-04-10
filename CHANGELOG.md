# Change Log

## 9.1.0 - 2025-04-10

Add support for raw JSON requests (like the ones used by the Assets UI) using SearchRequest::json.

## 9.0.0 - 2025-02-19

PHP 8.4 is now required.

## 8.1.3 - 2025-01-10

Fix double question mark in "forceDownload" URL query string.

The optional custom HTTP User-Agent can now be set via AssetsConfig. 

## 8.1.2 - 2024-11-22

PHP 8.4 is now supported.

Assume Assets is down on HTTP 502 / 503 / 504. Do not assume this on authentication failure.

Add test for AssetsHealth.

## 8.1.1 - 2024-11-13

Make AssetsHealth::isServiceUnavailableException() public.

## 8.1.0 - 2024-11-13

Add AssetsHealth class which can be used to integrate with a circuit breaker.

## 8.0.0 - 2024-11-13

Exception changes: Instead of wrapping them in an AssetsException, throw the original exception (e. g. GuzzleException).
(This reverts many changes made in 5.0.3.)
Throw an NotAuthorizedAssetsException when API login fails.

Fix PHP warning 'The "Twig\Extension\EscaperExtension::setEscaper()" method is deprecated'.

Fix missing separator in UpdateRequest log message.

## 7.1.0 - 2024-09-04

Add support for the new "keepUpdate" parameter (available since Assets Server 6.107) in UpdateRequest.

## 7.0.0 - 2024-05-16

Backward incompatible API changes: AssetsClient::rawServiceRequest() has been removed. AssetsClient::serviceRequest() 
parameters have been changed ($method is now required), and it returns an HTTP response object instead of an array.

Added an httpResponse property (that contains the raw Guzzle HTTP response object) to all Response objects.

All Request::__invoke() methods now return a Response object. The ones which returned void before return an EmptyResponse.

Added PingRequest.

## 6.3.1 - 2024-05-06

Updated dependencies: monolog/monolog from 3.2 to 3.6.

## 6.3.0 - 2024-05-06

Updated dependencies: phpunit/php-timer from 6.0 to 7.0, phpunit/phpunit from 10 to 11, guzzlehttp/guzzle from 7.5 to 7.8.

## 6.2.1 - 2024-04-30

Check for invalid characters in AssetId::isValid().

## 6.2.0 - 2024-02-27

Add AssetsUtils::replaceInvalidFilenameCharacters().

Improve error logging in CreateRequest, UpdateRequest.

## 6.1.0 - 2024-02-26

Fix CreateRequest::$parseMetadataModifications and CreateRequest::$metadataToReturn parameters being ignored. Add 
support for the CreateRequest::$autoRename parameter.

Fix SearchAssetRequest and UpdateRequest ignoring the $metadataToReturn parameter, always returning all metadata.

## 6.0.0 - 2024-01-10

Backward incompatible API change: Introducing the AssetId class. Asset IDs must now be passed as an AssetId instance
instead of a string, and are returned as AssetId (in AssetResponse).

Added support for some Assets Admin functionality: MetricsRequest, ActiveUsersRequest (private API).

## 5.1.1 - 2023-11-27

PHP 8.3 is now supported.

## 5.1.0 - 2023-11-03

Updated dependencies: phpunit/php-timer from 5.0 to 6.0, phpunit/phpunit from 9.5 to 10.

## 5.0.4 - 2023-09-27

Added RemoveRequest::$async, RemoveByIdRequest::$async parameters and ProcessResponse::$processId return value.
Fixed UpdateBulkRequest::$async parameter being ignored.

## 5.0.3 - 2023-08-18

Exception cleanup: Throw an AssetsException in more cases, get rid of must RuntimeException and GuzzleException 
instances. Throw more specific BadFunctionCallException instead of RuntimeException when invalid values are passed.
Works with both "true" and "false" in the Assets Cluster Property returnStatusOkOnErrorForServicesApi.

## 5.0.2 - 2023-08-17

Added support for SearchRequest::$returnThumbnailHits, AssetResponse::$thumbnailHits.
Added readthedocs.com configuration file.

## 5.0.1 - 2023-06-01

Rename Composer package from "der-spiegel/ww-elvis-client" to "der-spiegel/ww-assets-client".

## 5.0.0 - 2023-05-25

PHP 8.2 is now required.
Backwards incompatible changes:
AssetsClient::getConfig() is now AssetsClient::config, AssetsConfig::getUrl() is AssetsConfig::url.
To keep sanitizing parameters, use AssetsConfig::create() instead of new AssetsConfig().
Helper methods like AssetsClient::searchAsset() have been moved into dedicated request classes (Helper namespace).
Requests are not executed through AssetsClient anymore, they are now invoked directly.
Request classes have been moved into "Api", "Services" and "Helper" namespaces.
Requests and responses now use readonly properties instead of getters and setters.
CreateRelationRequest::relationType has changed from string to RelationType.

## 4.0.1 - 2023-03-10

Fixed UsageStatsRecord::details response parsing.
Fixed "intToDateTime" response conversion for timestamps divisible by 1,000.

## 4.0.0 - 2023-03-07

Backwards incompatible change: SearchResponse::getHits() now returns AssetResponseList instead of array.
Backwards incompatible change: CheckoutResponse::getCheckedOut() now returns ?DateTimeImmutable instead of int.
Added support for PHP 8.2.
Added support for the "history" API call with AssetsClient::history().
Added AssetsWebhookType enum.

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
