<?php

namespace KoninklijkeCollective\KoningGeo\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Utility: Geo configuration
 */
class ConfigurationUtility
{
    public const EXTENSION = 'koning_geo';

    /** @var array */
    public static $configuration;

    /**
     * @return array
     */
    public static function tableList(): array
    {
        $tableList = static::getConfiguration()['tableList'] ?? '';

        return GeneralUtility::trimExplode(',', $tableList, true);
    }

    /**
     * @return string|null
     */
    public static function googleApiKey(): ?string
    {
        return static::getConfiguration()['googleApiKey'] ?? null;
    }

    /**
     * @return array
     */
    public static function getConfiguration(): ?array
    {
        if (static::$configuration === null) {
            $data = $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS'][self::EXTENSION]
                ?? $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][self::EXTENSION]
                ?? null;
            if (!is_array($data)) {
                static::$configuration = unserialize($data) ?: [];
            } else {
                static::$configuration = $data;
            }
        }

        return static::$configuration;
    }
}
