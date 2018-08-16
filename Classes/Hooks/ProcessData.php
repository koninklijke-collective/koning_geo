<?php

namespace KoninklijkeCollective\KoningGeo\Hooks;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use KoninklijkeCollective\KoningGeo\Exception\AbstractException;
use KoninklijkeCollective\KoningGeo\Service\LocationService;
use KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility;
use KoninklijkeCollective\KoningGeo\Utility\GeoUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Hook: Process data
 *
 * @package KoninklijkeCollective\KoningGeo\Hooks
 */
class ProcessData
{
    const FIELD_NAME = 'koninggeo_selector';

    /**
     * @var array
     */
    protected $locationMapping = [];

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
            $this->locationMapping[$table][$id] = $incomingFieldArray[self::FIELD_NAME];
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
     * @param DataHandler $dataHandler
     * @return void
     * @throws \TYPO3\CMS\Core\Exception
     */
    public function processDatamap_afterDatabaseOperations($status, $table, $id, $fieldArray, DataHandler $dataHandler)
    {
        $message = null;
        if (isset($this->locationMapping[$table][$id])) {
            if ($status === 'new') {
                $locationForNewRecord = $this->locationMapping[$table][$id];
                unset($this->locationMapping[$table][$id]);

                $id = $dataHandler->substNEWwithIDs[$id];
                $this->locationMapping[$table][$id] = $locationForNewRecord;
            }

            $location = GeoUtility::getLocationData($id, $table);
            if ($location === null || $location->getLocation() !== $this->locationMapping[$table][$id]) {
                $this->getLocationService()->delete($id, $table);

                if (trim($this->locationMapping[$table][$id]) !== '') {
                    $geoData = GeoUtility::getDataForLocation($this->locationMapping[$table][$id]);
                    if ($geoData !== null) {
                        $location = Location::create($geoData)
                            ->setUidForeign($id)
                            ->setTablename($table);
                        if ($this->getLocationService()->create($location)) {
                            /** @var FlashMessage $message */
                            $message = GeneralUtility::makeInstance(
                                FlashMessage::class,
                                LocalizationUtility::translate(
                                    'LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.success.text',
                                    ConfigurationUtility::EXTENSION,
                                    [$this->locationMapping[$table][$id]]
                                ),
                                LocalizationUtility::translate(
                                    'LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.success.header',
                                    ConfigurationUtility::EXTENSION
                                )
                            );
                        }
                    } else {
                        /** @var FlashMessage $message */
                        $message = GeneralUtility::makeInstance(
                            FlashMessage::class,
                            LocalizationUtility::translate(
                                'LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.error.text',
                                ConfigurationUtility::EXTENSION,
                                [$this->locationMapping[$table][$id]]
                            ),
                            LocalizationUtility::translate(
                                'LLL:EXT:koning_geo/Resources/Private/Language/locallang_be.xlf:flash_message.error.header',
                                ConfigurationUtility::EXTENSION
                            ),
                            FlashMessage::ERROR
                        );
                    }

                    /* @var $flashMessageService FlashMessageService */
                    $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
                    $flashMessageService->getMessageQueueByIdentifier()->enqueue($message);
                }
            }
            unset($this->locationMapping[$table][$id]);
        }
    }

    /**
     * Remove location record when a record is removed
     * Copy location record when a record is copied
     *
     * @param string $command
     * @param string $table
     * @param int $id
     * @param string $value
     * @param DataHandler $dataHandler
     * @return void
     */
    public function processCmdmap_postProcess($command, $table, $id, $value, DataHandler $dataHandler)
    {
        switch ($command) {
            case 'delete':
                $this->getLocationService()->delete($id, $table);
                break;
            case 'copy':
                $configuration = ConfigurationUtility::getConfiguration();
                foreach (GeneralUtility::trimExplode(',', $configuration['tableList'], true) as $target) {
                    if ($dataHandler->copyMappingArray[$target]) {
                        foreach ($dataHandler->copyMappingArray[$target] as $oldId => $newId) {
                            try {
                                $location = $this->getLocationService()->get($oldId, $target);
                                $location->setUidForeign($newId);
                                $this->getLocationService()->create($location);
                            } catch (AbstractException $e) {
                                // Do nothing..
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
    protected function isInTableList($table): bool
    {
        if (ConfigurationUtility::isValid()) {
            $configuration = ConfigurationUtility::getConfiguration();
            return GeneralUtility::inList($configuration['tableList'], $table);
        }
        return false;
    }

    /**
     * @return LocationService
     */
    protected function getLocationService(): LocationService
    {
        return GeneralUtility::makeInstance(LocationService::class);
    }
}
