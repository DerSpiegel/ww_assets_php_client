<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsActionList;
use DerSpiegel\WoodWingAssetsClient\AssetsClient;
use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Exception;


enum HistoryDetailLevel: int
{
    case CustomActions = 0;
    case Level1 = 1;
    case Level2 = 2;
    case Level3 = 3;
    case Level4 = 4;
    case AllActions = 5;
}


/**
 * Get asset history
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 */
class HistoryRequest extends Request
{
    public function __construct(
        AssetsClient                $assetsClient,
        readonly string             $id = '',
        readonly ?AssetsActionList  $actions = null,
        readonly HistoryDetailLevel $detailLevel = HistoryDetailLevel::CustomActions,
        readonly int                $start = 0,
        readonly ?int               $num = null
    )
    {
        parent::__construct($assetsClient);
    }


    public function __invoke(): HistoryResponse
    {
        $data = [
            'id' => $this->id,
            'start' => $this->start,
            'detailLevel' => $this->detailLevel->value
        ];

        if ($this->num !== null) {
            $data['num'] = $this->num;
        }

        if (($this->detailLevel === HistoryDetailLevel::CustomActions) && (!empty($this->actions))) {
            $data['actions'] = implode(',', array_map(
                function ($value) {
                    return $value->value;
                },
                $this->actions->getArrayCopy()
            ));
        }

        try {
            $response = $this->assetsClient->serviceRequest(
                'asset/history',
                $data
            );
        } catch (Exception $e) {
            throw new AssetsException(
                sprintf(
                    '%s: Get history of asset <%s> failed: %s',
                    __METHOD__,
                    $this->id,
                    $e->getMessage()
                ),
                $e->getCode(),
                $e
            );
        }

        $this->logger->info(sprintf('Got history of asset <%s>', $this->id),
            [
                'method' => __METHOD__,
                'id' => $this->id
            ]
        );

        return HistoryResponse::createFromJson($response);
    }
}
