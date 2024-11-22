<?php

namespace DerSpiegel\WoodWingAssetsClient;

use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;


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
        // But
        // * if no connection can be established,
        // * or "502 Bad Gateway", "503 Service Unavailable" or "504 Gateway Timeout" is returned,
        // we assume that the service is down.

        return (
            ($exception instanceof ConnectException) ||
            (($exception instanceof ServerException) && in_array($exception->getCode(), [502, 503, 504]))
        );
    }
}
