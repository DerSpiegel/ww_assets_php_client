<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class CopyAssetRequest
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268731-Assets-Server-REST-API-copy
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class CopyAssetRequest extends Request
{
    const FILE_REPLACE_POLICY_AUTO_RENAME = 'AUTO_RENAME';
    const FILE_REPLACE_POLICY_OVERWRITE = 'OVERWRITE';
    const FILE_REPLACE_POLICY_OVERWRITE_IF_NEWER = 'OVERWRITE_IF_NEWER';
    const FILE_REPLACE_POLICY_REMOVE_SOURCE = 'REMOVE_SOURCE';
    const FILE_REPLACE_POLICY_THROW_EXCEPTION = 'THROW_EXCEPTION';
    const FILE_REPLACE_POLICY_DO_NOTHING = 'DO_NOTHING';

    protected string $source;

    protected string $target;

    protected string $fileReplacePolicy = self::FILE_REPLACE_POLICY_AUTO_RENAME;


    /**
     * Get source asset path
     * @return string
     */
    public function getSource(): string
    {
        return $this->source ?: '';
    }


    /**
     * Set source asset path
     * @param string $source
     * @return self
     */
    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }


    /**
     * Get source target path
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target ?: '';
    }


    /**
     * Set source target path
     * @param string $target
     * @return self
     */
    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }


    /**
     * @return string
     */
    public function getFileReplacePolicy(): string
    {
        return $this->fileReplacePolicy;
    }


    /**
     * @param string $fileReplacePolicy
     * @return self
     */
    public function setFileReplacePolicy(string $fileReplacePolicy): self
    {
        $this->fileReplacePolicy = $fileReplacePolicy;
        return $this;
    }
}
