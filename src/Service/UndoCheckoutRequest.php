<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use BadFunctionCallException;
use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Undo checkout
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268951-Assets-REST-API-undo-checkout
 */
class UndoCheckoutRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly ?AssetId $id = null
    ) {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if ($this->id === null) {
            throw new BadFunctionCallException(sprintf("%s: ID is empty in UndoCheckoutRequest", __METHOD__));
        }
    }


    public function __invoke(): EmptyResponse
    {
        $this->validate();

        $httpResponse = $this->assetsClient->serviceRequest(
            'POST',
            sprintf('undocheckout/%s', urlencode($this->id->id))
        );

        $this->logger->info(
            sprintf('Undo checkout for asset <%s> performed', $this->id->id),
            [
                'method' => __METHOD__,
                'id' => $this->id->id,
                'response' => $httpResponse
            ]
        );

        return EmptyResponse::createFromHttpResponse($httpResponse);
    }
}