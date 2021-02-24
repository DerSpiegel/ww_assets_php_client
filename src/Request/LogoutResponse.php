<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class LogoutResponse
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690286-Elvis-6-REST-API-logout
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class LogoutResponse extends Response
{
    protected bool $logoutSuccess;

    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        if (isset($json['logoutSuccess'])) {
            $this->logoutSuccess = $json['logoutSuccess'];
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isLogoutSuccess(): bool
    {
        return boolval($this->logoutSuccess);
    }
}
