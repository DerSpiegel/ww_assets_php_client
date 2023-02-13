<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class LogoutResponse
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268851-Assets-Server-REST-API-logout
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class LogoutResponse extends Response
{
    #[MapFromJson] protected bool $logoutSuccess = false;


    /**
     * @return bool
     */
    public function isLogoutSuccess(): bool
    {
        return $this->logoutSuccess;
    }
}
