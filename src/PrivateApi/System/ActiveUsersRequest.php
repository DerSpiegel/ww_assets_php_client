<?php

namespace DerSpiegel\WoodWingAssetsClient\PrivateApi\System;

use DerSpiegel\WoodWingAssetsClient\AssetsUtils;
use DerSpiegel\WoodWingAssetsClient\Request;


class ActiveUsersRequest extends Request
{
    public function __invoke(): array
    {
        $httpResponse = $this->assetsClient->privateApiRequest('GET', 'system/active-users');

        $responseBody = (string)$httpResponse->getBody();

        if (empty($responseBody)) {
            return [];
        }

        return AssetsUtils::parseJsonResponse($responseBody);
    }
}