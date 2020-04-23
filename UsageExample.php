<?php

use DerSpiegel\WoodWingElvisClient\ElvisConfig;
use DerSpiegel\WoodWingElvisClient\ElvisClient;
use DerSpiegel\WoodWingElvisClient\Request\SearchRequest;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

require_once 'vendor/autoload.php';

$logger = new Logger('elvisClient');
$logger->pushHandler(new ErrorLogHandler());

$elvisConfig = new ElvisConfig(
    'https://elvis.example.com/',
    'username',
    'password'
);

$elvisClient = new ElvisClient($elvisConfig, $logger);

$request = (new SearchRequest($elvisConfig))
    ->setQ('')
    ->setMetadataToReturn(['']);

$response = $elvisClient->search($request);

foreach ($response->getHits() as $assetResponse) {
    echo $assetResponse->getId() . "\n";
}
