<?php

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

require_once 'vendor/autoload.php';

$logger = new Logger('elvisClient');    // A logger is required
$logger->pushHandler(new ErrorLogHandler());  // Log to PHP error_log

$elvisConfig = new AssetsConfig(
    'https://elvis.example.com/', // Elvis URL (without app/ or services/ postfix)
    'username',              // Elvis user name (API user preferred)
    'password'               // That user's password
);

$elvisClient = new AssetsClient($elvisConfig, $logger); // Create client

$elvisClient->setHttpUserAgent(                        // Optional: Customize HTTP User-Agent
    'ExampleOrg/UsageExample ' . $elvisClient->getHttpUserAgent()
);

$request = (new SearchRequest($elvisConfig))           // Create search request
    ->setQ('')                                      // Elvis query
    ->setMetadataToReturn(['']);                       // Metadata fields to return

$response = $elvisClient->search($request);            // Perform search

foreach ($response->getHits() as $assetResponse) {     // Loop through results
    echo $assetResponse->getId() . "\n";               // Access asset metadata
}
