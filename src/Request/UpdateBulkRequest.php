<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


/**
 * Update metadata from a bunch of assets
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268991-Assets-Server-REST-API-updatebulk
 */
class UpdateBulkRequest extends CreateRequestBase
{
    protected string $q = '';
    protected bool $async = false;


    public function execute(): ProcessResponse
    {
        $requestData = [
            'q' => $this->getQ(),
            'metadata' => json_encode($this->getMetadata()),
            'parseMetadataModifications' => $this->isParseMetadataModification() ? 'true' : 'false'
        ];

        try {
            $response = $this->assetsClient->serviceRequest('updatebulk', $requestData);
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Update Bulk failed for query <%s> - <%s> - <%s>',
                    __METHOD__,
                    $this->getQ(),
                    $e->getMessage(),
                    json_encode($requestData)
                ),
                $e->getCode(),
                $e);
        }

        $this->logger->info(sprintf('Updated bulk for query <%s>', $this->getQ()),
            [
                'method' => __METHOD__,
                'query' => $this->getQ(),
                'metadata' => $this->getMetadata()
            ]
        );

        return (new ProcessResponse())->fromJson($response);
    }


    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q;
    }


    /**
     * @param string $q
     * @return self
     */
    public function setQ(string $q): self
    {
        $this->q = $q;
        return $this;
    }


    /**
     * @return bool
     */
    public function isAsync(): bool
    {
        return $this->async;
    }


    /**
     * @param bool $async
     * @return self
     */
    public function setAsync(bool $async): self
    {
        $this->async = $async;
        return $this;
    }
}
