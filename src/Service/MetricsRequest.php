<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\Request;


class MetricsRequest extends Request
{
    public function __invoke(): array
    {
        $httpResponse = $this->assetsClient->serviceRequest('POST', 'system/metrics');

        return AssetsUtils::parseJsonResponse($httpResponse->getBody());
    }
}