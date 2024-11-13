<?php

namespace DerSpiegel\WoodWingAssetsClient;

use DerSpiegel\WoodWingAssetsClient\Exception\NotAuthorizedAssetsException;
use Exception;
use GuzzleHttp\Exception\ConnectException;


class AssetsHealth
{
    protected bool $serviceIsAvailable = true;


    public function serviceIsAvailable(): ?bool
    {
        return $this->serviceIsAvailable;
    }


    public function setServiceIsAvailable(bool $isAvailable): void
    {
        $this->serviceIsAvailable = $isAvailable;
    }


    public function setServiceIsAvailableByException(Exception $exception): void
    {
        $this->setServiceIsAvailable(!$this->isServiceUnavailableException($exception));
    }


    public function isServiceUnavailableException(Exception $exception): bool
    {
        // A "400 Bad Request" or "500 Internal Server Error" might be request specific, so we cannot assume that
        // other requests won't work.
        // But if authentication fails or no connection can be established, it is likely that the service is down.

        return (($exception instanceof NotAuthorizedAssetsException) || ($exception instanceof ConnectException));
    }
}
