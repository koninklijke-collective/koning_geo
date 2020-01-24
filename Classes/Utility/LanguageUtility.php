<?php

namespace KoninklijkeCollective\KoningGeo\Utility;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class LanguageUtility
{
    /**
     * @param  string  $key
     * @param  array  $parameters
     * @param  mixed  $default
     * @return string|null
     */
    public static function translate(string $key, array $parameters = [], $default = null): ?string
    {
        return LocalizationUtility::translate(
            $key,
            ConfigurationUtility::EXTENSION,
            $parameters
        ) ?? $default;
    }
}
