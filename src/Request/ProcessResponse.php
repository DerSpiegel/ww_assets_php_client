<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class ProcessResponse
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class ProcessResponse extends Response
{
    #[MapFromJson] protected int $processedCount = 0;
    #[MapFromJson] protected int $errorCount = 0;


    /**
     * @return int
     */
    public function getProcessedCount(): int
    {
        return $this->processedCount;
    }


    /**
     * @return int
     */
    public function getErrorCount(): int
    {
        return $this->errorCount;
    }
}
