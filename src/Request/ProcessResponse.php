<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class ProcessResponse
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class ProcessResponse extends Response
{
    protected int $processedCount;

    protected int $errorCount;


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        if (isset($json['processedCount'])) {
            $this->processedCount = intval($json['processedCount']);
        }

        if (isset($json['errorCount'])) {
            $this->errorCount = intval($json['errorCount']);
        }

        return $this;
    }


    /**
     * @return int
     */
    public function getProcessedCount(): int
    {
        return intval($this->processedCount);
    }


    /**
     * @return int
     */
    public function getErrorCount(): int
    {
        return intval($this->errorCount);
    }
}
