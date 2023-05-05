<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Update an asset's metadata
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268971-Assets-Server-REST-API-update-check-in
 */
class UpdateRequest extends CreateRequestBase
{
    protected string $id = '';
    protected bool $clearCheckoutState = true;


    public function execute(): void
    {
        $requestData = [
            'id' => $this->getId(),
            'parseMetadataModifications' => $this->isParseMetadataModification() ? 'true' : 'false'
        ];

        $metadata = $this->getMetadata();

        if (count($metadata) > 0) {
            $requestData['metadata'] = json_encode($metadata);
        }

        $fp = $this->getFiledata();

        if (is_resource($fp)) {
            $requestData['Filedata'] = $fp;
            $requestData['clearCheckoutState'] = $this->isClearCheckoutState() ? 'true' : 'false';
        }

        try {
            $this->assetsClient->serviceRequest('update', $requestData);
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Update failed for asset <%s> - <%s> - <%s>',
                    __METHOD__,
                    $request->getId(),
                    $e->getMessage(),
                    json_encode($requestData)
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(
            sprintf(
                'Updated %s for asset <%s>',
                implode(array_intersect(['metadata', 'Filedata'], array_keys($requestData))),
                $this->getId()
            ),
            [
                'method' => __METHOD__,
                'assetId' => $this->getId(),
                'metadata' => $this->getMetadata()
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
     * @return bool
     */
    public function isClearCheckoutState(): bool
    {
        return $this->clearCheckoutState;
    }


    /**
     * @param bool $clearCheckoutState
     * @return self
     */
    public function setClearCheckoutState(bool $clearCheckoutState): self
    {
        $this->clearCheckoutState = $clearCheckoutState;
        return $this;
    }
}
