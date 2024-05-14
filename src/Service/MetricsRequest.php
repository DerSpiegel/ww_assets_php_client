<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\Request;


class MetricsRequest extends Request
{
    public function __invoke(): array
    {
        return $this->assetsClient->serviceRequest('POST', 'system/metrics');
    }
}