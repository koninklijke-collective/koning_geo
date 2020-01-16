<?php

use KoninklijkeCollective\KoningGeo\Hooks\ExtendDataHandler;
use KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

foreach (ConfigurationUtility::tableList() as $table) {
    if (isset($GLOBALS['TCA'][$table])) {
        ExtensionManagementUtility::addTCAcolumns($table, [
            ExtendDataHandler::FIELD_NAME => [
                'exclude' => true,
                'label' => 'LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:koning_geo_address_finder',
                'config' => [
                    'type' => 'select',
                    'renderType' => 'addressFinder',
                ],
            ],
        ]);
        ExtensionManagementUtility::addToAllTCAtypes(
            $table,
            '--div--;LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:tab.koning_geo, koning_geo_selector'
        );
    }
}
