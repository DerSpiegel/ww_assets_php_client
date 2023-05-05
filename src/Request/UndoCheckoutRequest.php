<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;
use RuntimeException;


/**
 * Undo checkout
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268951-Assets-REST-API-undo-checkout
 */
class UndoCheckoutRequest extends Request
{
    protected string $id = '';


    public function execute(): void
    {
        if (trim($this->getId()) === '') {
            throw new RuntimeException(sprintf("%s: ID is empty in UndoCheckoutRequest", __METHOD__));
        }

        try {
            $response = $this->assetsClient->serviceRequest(
                sprintf('undocheckout/%s', urlencode($this->getId()))
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Undo checkout of asset <%s> failed',
                    __METHOD__,
                    $this->getId()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Undo checkout for asset <%s> performed', $this->getId()),
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
}