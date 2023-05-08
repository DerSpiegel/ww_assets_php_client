<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;
use RuntimeException;


/**
 * Promote version
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/4824964597009-Assets-Server-REST-API-promote
 */
class PromoteRequest extends Request
{
    protected string $id = '';
    protected int $version = 0;


    public function validate(): void
    {
        if (trim($this->getId()) === '') {
            throw new RuntimeException(sprintf("%s: ID is empty in UndoCheckoutRequest", __METHOD__));
        }

        if ($this->getVersion() < 1) {
            throw new RuntimeException(sprintf("%s: Version is empty in UndoCheckoutRequest", __METHOD__));
        }
    }


    public function execute(): void
    {
        $this->validate();

        try {
            $response = $this->assetsClient->serviceRequest(
                'version/promote',
                [
                    'assetId' => $this->getId(),
                    'version' => $this->getVersion()
                ]
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Promote version <%d> of asset <%s> failed',
                    __METHOD__,
                    $this->getVersion(),
                    $this->getId()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Promote version <%d> for asset <%s> performed', $this->getVersion(), $this->getId()),
            [
                'method' => __METHOD__,
                'id' => $this->getId(),
                'response' => $response
            ]
        );
    }


    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }


    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }


    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }


    /**
     * @param int $version
     * @return PromoteRequest
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }
}