<?php

namespace FreeElephants\HexammonServer\Auth\Endpoint\DTO\User;

use FreeElephants\HexammonServer\Auth\Model\User\User;
use NilPortugues\Api\Mappings\HalMapping;

/**
 * @author samizdam <samizdam@inbox.ru>
 */
class UserMapping implements HalMapping
{

    /**
     * Returns a string with the full class name, including namespace.
     *
     * @return string
     */
    public function getClass()
    {
        return User::class;
    }

    /**
     * Returns a string representing the resource name as it will be shown after the mapping.
     *
     * @return string
     */
    public function getAlias()
    {
        return 'user';
    }

    /**
     * Returns an array of properties that will be renamed.
     * Key is current property from the class. Value is the property's alias name.
     *
     * @return array
     */
    public function getAliasedProperties()
    {
        return [];
    }

    /**
     * List of properties in the class that will be ignored by the mapping.
     *
     * @return array
     */
    public function getHideProperties()
    {
        return [
            'email',
            'passwordHash',
        ];
    }

    /**
     * Returns an array of properties that are used as an ID value.
     *
     * @return array
     */
    public function getIdProperties()
    {
        return ['id'];
    }

    /**
     * Returns a list of URLs. This urls must have placeholders to be replaced with the getIdProperties() values.
     *
     * @return array
     */
    public function getUrls()
    {
        return [
            'self' => '/api/v1/users/{id}'
        ];
    }

    /**
     * Returns an array of curies.
     *
     * @return array
     */
    public function getCuries()
    {
        return [];
    }
}