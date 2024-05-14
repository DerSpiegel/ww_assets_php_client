<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use DerSpiegel\WoodWingAssetsClient\Request;
use Exception;


/**
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269271-Configuring-Assets-Server-session-time-outs
 */
class PingRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly string $uid = '',
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): EmptyResponse
    {
        try {
            $httpResponse = $this->assetsClient->serviceRequest('GET', 'ping', $this->toArray());
        } catch (Exception $e) {
            throw new AssetsException(sprintf('%s: Ping failed: <%s>', __METHOD__, $e->getMessage()), $e->getCode(),
                $e);
        }

        $this->logger->debug('Ping performed',
            [
                'method' => __METHOD__,
                'uid' => $this->uid
            ]
        );

        return EmptyResponse::createFromHttpResponse($httpResponse);
    }


    protected function toArray(): array
    {
        $params = [
            'uid' => $this->uid
        ];

        return $params;
    }
}
