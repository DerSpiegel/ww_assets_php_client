<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use BadFunctionCallException;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use Exception;


/**
 * Undo checkout
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268951-Assets-REST-API-undo-checkout
 */
class UndoCheckoutRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $id = ''
    )
    {
        parent::__construct($assetsClient);
    }


    public function validate(): void
    {
        if (trim($this->id) === '') {
            throw new BadFunctionCallException(sprintf("%s: ID is empty in UndoCheckoutRequest", __METHOD__));
        }
    }


    public function __invoke(): void
    {
        $this->validate();

        try {
            $response = $this->assetsClient->serviceRequest(
                sprintf('undocheckout/%s', urlencode($this->id))
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Undo checkout of asset <%s> failed',
                    __METHOD__,
                    $this->id
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Undo checkout for asset <%s> performed', $this->id),
            [
                'method' => __METHOD__,
                'id' => $this->id,
                'response' => $response
            ]
        );
    }
}