<?php

namespace DerSpiegel\WoodWingElvisClient\Request;


/**
 * Class RemoveRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002663483-Elvis-6-REST-API-remove
 * @package DerSpiegel\WoodWingElvisClient\Request
 */
class RemoveRequest extends Request
{
    protected string $q;

    // TODO: Shouldn't this be an array instead of a comma-separated string?
    protected string $ids;

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
     * @return string
     */
    public function getIds(): string
    {
        return $this->ids ?? '';
    }


    /**
     * @param string $ids
     * @return self
     */
    public function setIds(string $ids): self
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
