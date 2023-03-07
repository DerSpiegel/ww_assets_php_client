<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;

use DerSpiegel\WoodWingAssetsClient\AssetsActionList;


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
 * Class HistoryRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042269011-Assets-Server-REST-API-Versioning-and-history
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class HistoryRequest extends Request
{
    protected ?AssetsActionList $actions = null;
    protected HistoryDetailLevel $detailLevel = HistoryDetailLevel::CustomActions;
    protected string $id = '';
    protected int $start = 0;
    protected ?int $num = null;


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
     * @return AssetsActionList|null
     */
    public function getActions(): ?AssetsActionList
    {
        return $this->actions;
    }

    /**
     * @param AssetsActionList|null $actions
     * @return self
     */
    public function setActions(?AssetsActionList $actions): self
    {
        $this->actions = $actions;
        return $this;
    }

    /**
     * @return HistoryDetailLevel
     */
    public function getDetailLevel(): HistoryDetailLevel
    {
        return $this->detailLevel;
    }

    /**
     * @param HistoryDetailLevel $detailLevel
     * @return self
     */
    public function setDetailLevel(HistoryDetailLevel $detailLevel): self
    {
        $this->detailLevel = $detailLevel;
        return $this;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return self
     */
    public function setStart(int $start): self
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNum(): ?int
    {
        return $this->num;
    }

    /**
     * @param int|null $num
     * @return self
     */
    public function setNum(?int $num): self
    {
        $this->num = $num;
        return $this;
    }
}