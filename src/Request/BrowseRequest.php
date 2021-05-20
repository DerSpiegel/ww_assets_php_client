<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class SearchRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class BrowseRequest extends Request
{
    const INCLUDE_FOLDERS_DEFAULT = true;

    protected string $fromRoot = '';
    protected bool $includeFolders = self::INCLUDE_FOLDERS_DEFAULT;
    protected string $path = '';


    /**
     * @return array
     */
    public function toArray(): array
    {
        $params = [
            'path' => $this->getPath()
        ];

        if (!empty($this->getFromRoot())) {
            $params['fromRoot'] = $this->getFromRoot();
        }

        if ($this->isIncludeFolders() !== self::INCLUDE_FOLDERS_DEFAULT) {
            $params['includeFolders'] = ($this->isIncludeFolders() ? 'true' : 'false');
        }

        return $params;
    }


    /**
     * @return string
     */
    public function toQueryString(): string
    {
        return http_build_query($this->toArray());
    }


    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }


    /**
     * @param string $path
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }


    /**
     * @return string
     */
    public function getFromRoot(): string
    {
        return $this->fromRoot;
    }


    /**
     * @param string $fromRoot
     * @return self
     */
    public function setFromRoot(string $fromRoot): self
    {
        $this->fromRoot = $fromRoot;
        return $this;
    }


    /**
     * @return bool
     */
    public function isIncludeFolders(): bool
    {
        return $this->includeFolders;
    }


    /**
     * @param bool $includeFolders
     * @return self
     */
    public function setIncludeFolders(bool $includeFolders): self
    {
        $this->includeFolders = $includeFolders;
        return $this;
    }
}
