<?php

namespace DerSpiegel\WoodWingAssetsClient;

use DerSpiegel\WoodWingAssetsClient\Exception\AssetsException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\SyntaxError;
use Twig\Extension\EscaperExtension;
use Twig\Extension\SandboxExtension;
use Twig\Loader\ArrayLoader;
use Twig\Sandbox\SecurityPolicy;
use Twig\TemplateWrapper;


/**
 * Class AssetsUtils
 * @package DerSpiegel\WoodWingAssetsClient
 */
class AssetsUtils
{
    /**
     * Parse JSON response string into array, throw exception on error response
     *
     * @see https://helpcenter.woodwing.com/hc/en-us/articles/360041851272-Assets-Server-REST-API-error-handling
     * @param string $jsonString
     * @return array
     */
    public static function parseJsonResponse(string $jsonString): array
    {
        $json = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);

        if (isset($json['errorcode']) && isset($json['message'])) {
            throw AssetsException::createFromCode($json['message'], intval($json['errorcode']));
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
                    $oldValue = (strlen((string)$oldValue) === 0) ? [] : [$oldValue];
                } else {
                    $oldValue = array_unique($oldValue);
                }

                if (!is_array($newValue)) {
                    $newValue = (strlen($newValue) === 0) ? [] : [$newValue];
                } else {
                    $newValue = array_unique($newValue);
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


    /**
     * Escape a query term for use with Elasticsearch
     *
     * @param string $queryTerm Query term
     * @return string Escaped query term
     * @see https://stackoverflow.com/questions/33845230/escape-elasticsearch-special-characters-in-php
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/5.5/query-dsl-query-string-query.html#_reserved_characters
     */
    public static function escapeForElasticsearch(string $queryTerm): string
    {
        static $keys = array();
        static $values = array();

        if (empty($keys)) {
            $replacements = array(
                ">" => "", // cannot be safely encoded
                "<" => "", // cannot be safely encoded
                "\\" => "\\\\", // must be done first to not double encode later backslashes!
                "+" => "\\+",
                "-" => "\\-",
                "=" => "\\=",
                "&" => "\\&",
                "|" => "\\|",
                "!" => "\\!",
                "(" => "\\(",
                ")" => "\\)",
                "{" => "\\{",
                "}" => "\\}",
                "[" => "\\[",
                "]" => "\\]",
                "^" => "\\^",
                "\"" => "\\\"",
                "~" => "\\~",
                "*" => "\\*",
                "?" => "\\?",
                ":" => "\\:",
                "/" => "\\/",
            );

            $keys = array_keys($replacements);
            $values = array_values($replacements);
        }

        return str_replace($keys, $values, $queryTerm);
    }


    /**
     * Get a Twig template for building an Assets query
     *
     * Call render($templateVariables) on the returned object to get the query string.
     *
     * @param string $templateString
     * @param array $allowedTags
     * @param array $allowedFilters
     * @return TemplateWrapper
     * @throws LoaderError
     * @throws SyntaxError
     */
    public static function getQueryTemplate(string $templateString, array $allowedTags = [], array $allowedFilters = []): TemplateWrapper
    {
        // Assuming that always recreating the Twig environment and template does not leak memory

        $twig = new Environment(
            new ArrayLoader(),
            ['cache' => false]
        );

        // Whitelist "safe" tags and filters

        $twig->addExtension(
            new SandboxExtension(
                new SecurityPolicy(
                    array_merge(['if'], $allowedTags),
                    array_merge(['escape', 'raw'], $allowedFilters)
                ),
                true
            )
        );

        // Add Elasticsearch escape filter, and make it the default

        $escaper = $twig->getExtension(EscaperExtension::class);

        $escaper->setEscaper('query',
            function ($twig, $string, $charset) {
                return AssetsUtils::escapeForElasticsearch($string ?? '');
            });

        $escaper->setDefaultStrategy('query');

        return $twig->createTemplate($templateString);
    }
}
