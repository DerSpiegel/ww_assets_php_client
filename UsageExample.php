<?php

use DerSpiegel\WoodWingAssetsClient\AssetsConfig;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request\SearchRequest;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

require_once 'vendor/autoload.php';

$logger = new Logger('assetsClient');    // A logger is required
$logger->pushHandler(new ErrorLogHandler());  // Log to PHP error_log

$assetsConfig = new AssetsConfig(
    'https://assets.example.com/', // Assets URL (without app/ or services/ postfix)
    'username',               // Assets user name (API user preferred)
    'password'                // That user's password
);

$assetsClient = new AssetsClient($assetsConfig, $logger); // Create client

$assetsClient->setHttpUserAgent(                     // Optional: Customize HTTP User-Agent
    'ExampleOrg/UsageExample ' . $assetsClient->getHttpUserAgent()
);

$request = new SearchRequest($assetsClient,          // Create search request
        q: '',                                       // Assets query
        metadataToReturn: ['']                       // Metadata fields to return
);

$response = $request();                              // Perform search

foreach ($response->getHits() as $assetResponse) {   // Loop through results
    echo $assetResponse->getId() . "\n";             // Access asset metadata
}
