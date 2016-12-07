<?php
call_user_func(function($extensionKey) {
    if (\KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility::isValid()) {
        $tempColumns = [
            'koninggeo_selector' => [
                'exclude' => 0,
                'label' => 'LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:koninggeo_selector',
                'config' => [
                    'type' => 'input',
                    'wizards' => [
                        'specialWizard' => [
                            'type' => 'userFunc',
                            'userFunc' => 'KoninklijkeCollective\KoningGeo\Wizards\SelectorField->render'
                        ]
                    ]
                ]
            ]
        ];

        $configuration = \KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility::getConfiguration();

        $tableList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $configuration['tableList']);
        foreach ($tableList as $table) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $tempColumns);
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes($table, '--div--;LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:tab.koning_geo, koninggeo_selector');
        }

        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$extensionKey] = \KoninklijkeCollective\KoningGeo\Hooks\ProcessData::class;
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extensionKey] = \KoninklijkeCollective\KoningGeo\Hooks\ProcessData::class;
    }
}, 'koning_geo');
