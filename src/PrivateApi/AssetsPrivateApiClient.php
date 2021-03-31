<?php

namespace DerSpiegel\WoodWingAssetsClient\PrivateApi;

use DerSpiegel\WoodWingAssetsClient\AssetsClientBase;
use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use JsonException;


class AssetsPrivateApiClient extends AssetsClientBase
{
    /**
     * @return array
     * @throws JsonException
     */
    public function getActiveUsers(): array
    {
        $url = sprintf(
            '%sprivate-api/system/active-users',
            $this->config->getUrl()
        );

        $httpResponse = $this->request(
            'GET',
            $url
        );

        return AssetsUtils::parseJsonResponse($httpResponse->getBody());
    }
}