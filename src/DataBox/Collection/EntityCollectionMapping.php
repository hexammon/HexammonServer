<?php

namespace FreeElephants\DataBox\Collection;

use NilPortugues\Api\Mappings\HalMapping;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class EntityCollectionMapping implements HalMapping
{

    public function getClass()
    {
        // TODO: Implement getClass() method.
    }

    /**
     * Returns a string representing the resource name as it will be shown after the mapping.
     *
     * @return string
     */
    public function getAlias()
    {
        // TODO: Implement getAlias() method.
    }

    /**
     * Returns an array of properties that will be renamed.
     * Key is current property from the class. Value is the property's alias name.
     *
     * @return array
     */
    public function getAliasedProperties()
    {
        // TODO: Implement getAliasedProperties() method.
    }

    /**
     * List of properties in the class that will be ignored by the mapping.
     *
     * @return array
     */
    public function getHideProperties()
    {
        // TODO: Implement getHideProperties() method.
    }

    /**
     * Returns an array of properties that are used as an ID value.
     *
     * @return array
     */
    public function getIdProperties()
    {
        // TODO: Implement getIdProperties() method.
    }

    /**
     * Returns a list of URLs. This urls must have placeholders to be replaced with the getIdProperties() values.
     *
     * @return array
     */
    public function getUrls()
    {
        // TODO: Implement getUrls() method.
    }

    /**
     * Returns an array of curies.
     *
     * @return array
     */
    public function getCuries()
    {
        // TODO: Implement getCuries() method.
    }
}