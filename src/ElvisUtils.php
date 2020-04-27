<?php


namespace DerSpiegel\WoodWingElvisClient;

use RuntimeException;


/**
 * Class ElvisUtils
 * @package DerSpiegel\WoodWingElvisClient
 */
class ElvisUtils
{

    /**
     * @param string $id
     * @return bool
     */
    public static function isElvisId(string $id): bool
    {
        return (strlen($id) === 22);
    }


    /**
     * Parse JSON response string into array, throw exception on error response
     *
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/115002690246-Elvis-6-REST-API-error-handling
     * @param string $jsonString
     * @return array
     */
    public static function parseJsonResponse(string $jsonString): array
    {
        $json = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);

        if (isset($json['errorcode']) && isset($json['message'])) {
            throw new RuntimeException(sprintf('%s: Elvis error: %s', __METHOD__, $json['message']),
                $json['errorcode']);
        }

        return $json;
    }


    /**
     * @param array $currentMetadata
     * @param array $updateMetadata
     */
    public static function cleanUpUnchangedMetadataFields(array &$updateMetadata, array $currentMetadata): void
    {
        foreach ($updateMetadata as $key => $newValue) {
            if (isset($currentMetadata[$key]['value'])) {
                // case of dates, sizes, and any formatted metadata fields
                $oldValue = $currentMetadata[$key]['value'];
            } elseif (isset($currentMetadata[$key])) {
                // all other cases
                $oldValue = $currentMetadata[$key];
            } else {
                // is not defined at all yet
                $oldValue = null;
            }

            $isArray = is_array($oldValue) || is_array($newValue);

            if ($isArray) {
                // Special handling for arrays of values: We don't care about the order of values

                if (!is_array($oldValue)) {
                    $oldValue = (strlen($oldValue) === 0) ? [] : [$oldValue];
                }

                if (!is_array($newValue)) {
                    $newValue = (strlen($newValue) === 0) ? [] : [$newValue];
                }

                if ((count($oldValue) === count($newValue)) && empty(array_diff($oldValue, $newValue))) {
                    unset($updateMetadata[$key]);
                }
            } else {
                // String representations should be good to compare (?)

                if ((string)$oldValue === (string)$newValue) {
                    unset($updateMetadata[$key]);
                }
            }
        }
    }
}
