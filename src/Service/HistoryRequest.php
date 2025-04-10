<?php

namespace DerSpiegel\WoodWingAssetsClient\Service;

use DerSpiegel\WoodWingAssetsClient\AssetId;
use DerSpiegel\WoodWingAssetsClient\AssetsActionList;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Get asset history
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 */
class HistoryRequest extends Request
{
    public function __construct(
        AssetsClient $assetsClient,
        readonly ?AssetId $id = null,
        readonly ?AssetsActionList $actions = null,
        readonly HistoryDetailLevel $detailLevel = HistoryDetailLevel::CustomActions,
        readonly int $start = 0,
        readonly ?int $num = null
    ) {
        parent::__construct($assetsClient);
    }


    public function __invoke(): HistoryResponse
    {
        $data = [
            'id' => $this->id->id,
            'start' => $this->start,
            'detailLevel' => $this->detailLevel->value
        ];

        if ($this->num !== null) {
            $data['num'] = $this->num;
        }

        if (($this->detailLevel === HistoryDetailLevel::CustomActions) && (!empty($this->actions))) {
            $data['actions'] = implode(
                ',',
                array_map(
                    function ($value) {
                        return $value->value;
                    },
                    $this->actions->getArrayCopy()
                )
            );
        }

        $httpResponse = $this->assetsClient->serviceRequest(
            'POST',
            'asset/history',
            $data
        );

        $this->logger->info(
            sprintf('Got history of asset <%s>', $this->id->id),
            [
                'method' => __METHOD__,
                'id' => $this->id->id
            ]
        );

        return HistoryResponse::createFromHttpResponse($httpResponse);
    }
}
