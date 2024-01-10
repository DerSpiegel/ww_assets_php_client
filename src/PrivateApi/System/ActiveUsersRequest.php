<?php

namespace DerSpiegel\WoodWingAssetsClient\PrivateApi\System;

use DerSpiegel\WoodWingAssetsClient\Request;


class ActiveUsersRequest extends Request
{
    public function __invoke(): array
    {
        return $this->assetsClient->privateApiRequest('GET', 'system/active-users');
    }
}