<?php

namespace DerSpiegel\WoodWingAssetsClient\Request;


/**
 * Class BrowseResponse
 *
 * @see https://helpcenter.woodwing.com/hc/en-us/articles/360042268711-Assets-Server-REST-API-browse
 * @package DerSpiegel\WoodWingAssetsClient\Request
 */
class BrowseResponse extends Response
{
    protected array $items = [];


    /**
     * @param array $json
     * @return self
     */
    public function fromJson(array $json): self
    {
        $this->items = [];

        foreach ($json as $item) {
            if (is_array($item)) {
                $this->items[] = $item;
            }
        }

        return $this;
    }


    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
