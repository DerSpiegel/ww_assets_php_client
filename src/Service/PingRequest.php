<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269271-Configuring-Assets-Server-session-time-outs
 */
class PingRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $uid = '',
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): EmptyResponse
    {
        $httpResponse = $this->assetsClient->serviceRequest('GET', 'ping', $this->toArray());

        $this->logger->debug(
            'Ping performed',
            [
                'method' => __METHOD__,
                'uid' => $this->uid
            ]
        );

        return EmptyResponse::createFromHttpResponse($httpResponse);
    }


    protected function toArray(): array
    {
        return [
            'uid' => $this->uid
        ];
    }
}
