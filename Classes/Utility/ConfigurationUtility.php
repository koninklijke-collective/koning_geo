<?php

namespace KoninklijkeCollective\KoningGeo\Utility;

/**
 * Utility: Geo configuration
 *
 * @package Keizer\KoningGeo\Utility
 */
class ConfigurationUtility
{
    /**
     * @return boolean
     */
    public static function isValid()
    {
        $configuration = static::getConfiguration();
        return (is_array($configuration)
            && !empty($configuration['tableList'])
            && !empty($configuration['googleMapsApiKey'])
        );
    }

    /**
     * @return array
     */
    public static function getConfiguration()
    {
        static $configuration;
        if ($configuration === null) {
            $data = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['koning_geo'];
            if (!is_array($data)) {
                $configuration = (array)unserialize($data);
            } else {
                $configuration = $data;
            }
        }
        return $configuration;
    }
}
