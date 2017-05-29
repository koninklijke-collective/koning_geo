<?php
call_user_func(function () {
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
            if (isset($GLOBALS['TCA'][$table])){
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($table, $tempColumns);
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes($table,
                    '--div--;LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:tab.koning_geo, koninggeo_selector');
            }
        }
    }
});
