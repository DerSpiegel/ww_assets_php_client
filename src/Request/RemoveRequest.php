<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class RemoveRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851352-Assets-Server-REST-API-remove
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class RemoveRequest extends Request
{
    protected string $q;

    protected array $ids = [];

    protected string $folderPath;


    /**
     * @return string
     */
    public function getQ(): string
    {
        return $this->q ?? '';
    }


    /**
     * @param string $q
     * @return self
     */
    public function setQ($q): self
    {
        $this->q = $q;
        return $this;
    }


    /**
     * @return string[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }


    /**
     * @param string[] $ids
     * @return self
     */
    public function setIds(array $ids): self
    {
        $this->ids = $ids;
        return $this;
    }


    /**
     * @return string
     */
    public function getFolderPath(): string
    {
        return $this->folderPath ?? '';
    }


    /**
     * @param string $folderPath
     * @return self
     */
    public function setFolderPath(string $folderPath): self
    {
        $this->folderPath = $folderPath;
        return $this;
    }
}
