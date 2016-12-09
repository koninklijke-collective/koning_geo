<?php
namespace KoninklijkeCollective\KoningGeo\Hooks;

/**
 * Hook: Process data
 *
 * @package KoninklijkeCollective\KoningGeo\Hooks
 */
class ProcessData
{
    const FIELD_NAME = 'koninggeo_selector';

    /**
     * @var string
     */
    protected $location = null;

    /**
     * Unset the field before save and save it in a class property (because the field doesn't exist in the database)
     *
     * @param array $incomingFieldArray
     * @param string $table
     * @param int $id
     * @return void
     */
    public function processDatamap_preProcessFieldArray(array &$incomingFieldArray, $table, $id)
    {
        if ($this->isInTableList($table) && isset($incomingFieldArray[self::FIELD_NAME])) {
            $this->location = $incomingFieldArray[self::FIELD_NAME];
            unset($incomingFieldArray[self::FIELD_NAME]);
        }
    }

    /**
     * Save the location record (if found) and set up the relation
     *
     * @param string $status
     * @param string $table
     * @param int $id
     * @param array $fieldArray
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
     * @return void
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler)
    {
        $message = null;
        if ($this->location !== null) {
            if ($status === 'new') {
                $id = $dataHandler->substNEWwithIDs[$id];
            }

            $location = \KoninklijkeCollective\KoningGeo\Utility\GeoUtility::getLocationData($id, $table);
            if ($location === null || $location->getLocation() !== $this->location) {
                $this->getDatabaseConnection()->exec_DELETEquery(
                    'tx_koninggeo_domain_model_location',
                    'uid_foreign = ' . (int)$id . ' AND tablename = ' . $this->getDatabaseConnection()->fullQuoteStr($table, 'tx_koninggeo_domain_model_location')
                );

                if (trim($this->location) !== '') {
                    $geoData = \KoninklijkeCollective\KoningGeo\Utility\GeoUtility::getDataForLocation($this->location);
                    if ($geoData !== null) {
                        $fields = array_merge(
                            [
                                'uid_foreign' => $id,
                                'tablename' => $table
                            ],
                            $geoData
                        );
                        $this->getDatabaseConnection()->exec_INSERTquery(
                            'tx_koninggeo_domain_model_location',
                            $fields
                        );

                        /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $message */
                        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class,
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.success.text', 'koning_geo'),
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.success.header', 'koning_geo')
                        );
                    } else {
                        /** @var \TYPO3\CMS\Core\Messaging\FlashMessage $message */
                        $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class,
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.error.text', 'koning_geo'),
                            \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate('LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.error.header', 'koning_geo'),
                            \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
                        );
                    }

                    /* @var $flashMessageService \TYPO3\CMS\Core\Messaging\FlashMessageService */
                    $flashMessageService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                    $flashMessageService->getMessageQueueByIdentifier()->enqueue($message);
                }
            }
        }
    }

    /**
     * Remove location record when a record is removed
     *
     * Copy location record when a record is copied
     *
     * @param string $command
     * @param string $table
     * @param int $id
     * @param string $value
     * @param \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler
     * @return void
     */
    public function processCmdmap_postProcess($command, $table, $id, $value, \TYPO3\CMS\Core\DataHandling\DataHandler $dataHandler) {
        if ($this->isInTableList($table)) {
            switch ($command) {
                case 'delete':
                    $this->getDatabaseConnection()->exec_DELETEquery(
                        'tx_koninggeo_domain_model_location',
                        'uid_foreign = ' . (int) $id . ' AND tablename = ' . $this->getDatabaseConnection()->fullQuoteStr($table, 'tx_koninggeo_domain_model_location')
                    );
                    break;
                case 'copy':
                    $configuration = \KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility::getConfiguration();
                    foreach (\TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $configuration['tableList']) as $table) {
                        if ($dataHandler->copyMappingArray[$table]) {
                            foreach ($dataHandler->copyMappingArray[$table] as $oldId => $newId) {
                                $existingRecord = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                                    '*',
                                    'tx_koninggeo_domain_model_location',
                                    'uid_foreign = ' . (int) $oldId . ' AND tablename = ' . $this->getDatabaseConnection()->fullQuoteStr($table, 'tx_koninggeo_domain_model_location')
                                );
                                if (is_array($existingRecord)) {
                                    $existingRecord['uid_foreign'] = $newId;
                                    $this->getDatabaseConnection()->exec_INSERTquery(
                                        'tx_koninggeo_domain_model_location',
                                        $existingRecord
                                    );
                                }
                            }
                        }
                    }
            }
        }
    }

    /**
     * @param string $table
     * @return boolean
     */
    protected function isInTableList($table)
    {
        if (\KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility::isValid()) {
            $configuration = \KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility::getConfiguration();
            $tableList = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $configuration['tableList']);
            return in_array($table, $tableList);
        }
        return false;
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
