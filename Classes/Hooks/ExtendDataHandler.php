<?php

namespace KoninklijkeCollective\KoningGeo\Hooks;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use KoninklijkeCollective\KoningGeo\Domain\Repository\LocationRepository;
use KoninklijkeCollective\KoningGeo\Exception\ExtensionException;
use KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Hook: Process data
 */
class ExtendDataHandler
{
    public const FIELD_NAME = 'koning_geo_selector';

    /** @var array */
    protected $locationMapping = [];

    /**
     * Unset the field before save and save it in a class property (because the field doesn't exist in the database)
     *
     * @param  array  $incomingFieldArray
     * @param  string  $table
     * @param  int  $id
     * @return void
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function processDatamap_preProcessFieldArray(array &$incomingFieldArray, string $table, int $id): void
    {
        if (isset($incomingFieldArray[self::FIELD_NAME]) && $this->isInTableList($table)) {
            $this->locationMapping[$table][$id] = json_decode($incomingFieldArray[self::FIELD_NAME], true);
            unset($incomingFieldArray[self::FIELD_NAME]);
        }
    }

    /**
     * Save the location record (if found) and set up the relation
     *
     * @param  string  $status
     * @param  string  $table
     * @param  int  $id
     * @param  array  $fieldArray
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler  $dataHandler
     * @return void
     * @throws \TYPO3\CMS\Core\Exception
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function processDatamap_afterDatabaseOperations(
        string $status,
        string $table,
        int $id,
        array $fieldArray,
        DataHandler $dataHandler
    ): void {
        $message = null;
        if (isset($this->locationMapping[$table][$id])) {
            if ($status === 'new') {
                $locationForNewRecord = $this->locationMapping[$table][$id];
                unset($this->locationMapping[$table][$id]);

                $id = $dataHandler->substNEWwithIDs[$id];
                $this->locationMapping[$table][$id] = $locationForNewRecord;
            }

            $response = $this->locationMapping[$table][$id];

            try {
                $location = $this->getLocationRepository()->get($id, $table);

                // Check if saved location is different as incoming data
                if ($location && $location->getPlaceId() === $response['place_id']) {
                    return;
                }
            } catch (ExtensionException $e) {
                $location = null;
            }

            // Create a new location
            $location = Location::createForResponse($id, $table, $response);

            $this->getLocationRepository()->createOrUpdate($location);

            unset($this->locationMapping[$table][$id]);
        }
    }

    /**
     * Remove location record when a record is removed
     * Copy location record when a record is copied
     *
     * @param  string  $command
     * @param  string  $table
     * @param  int  $id
     * @param  string  $value
     * @param  \TYPO3\CMS\Core\DataHandling\DataHandler  $dataHandler
     * @return void
     */
    // phpcs:ignore PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    public function processCmdmap_postProcess(
        string $command,
        string $table,
        int $id,
        string $value,
        DataHandler $dataHandler
    ): void {
        switch ($command) {
            case 'delete':
                $this->getLocationRepository()->delete($id, $table);
                break;

            case 'copy':
                $configuration = ConfigurationUtility::getConfiguration();
                foreach (GeneralUtility::trimExplode(',', $configuration['tableList'], true) as $target) {
                    if ($dataHandler->copyMappingArray[$target]) {
                        foreach ($dataHandler->copyMappingArray[$target] as $oldId => $newId) {
                            try {
                                $location = $this->getLocationRepository()->get($oldId, $target);
                                $location->setUidForeign($newId);
                                $this->getLocationRepository()->create($location);
                            } catch (ExtensionException $e) {
                                // Do nothing..
                            }
                        }
                    }
                }
        }
    }

    /**
     * @param  string  $table
     * @return bool
     */
    protected function isInTableList(string $table): bool
    {
        return in_array($table, ConfigurationUtility::tableList(), true);
    }

    /**
     * @return \KoninklijkeCollective\KoningGeo\Domain\Repository\LocationRepository
     */
    protected function getLocationRepository(): LocationRepository
    {
        return GeneralUtility::makeInstance(LocationRepository::class);
    }
}
