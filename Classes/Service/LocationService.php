<?php

namespace KoninklijkeCollective\KoningGeo\Service;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use KoninklijkeCollective\KoningGeo\Exception;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Service: Location retrieval interaction
 *
 * @package KoninklijkeCollective\KoningGeo\Service
 */
class LocationService
{
    /**
     * Retrieve location object from database
     *
     * @param integer $identifier
     * @param string $table
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     * @throws \KoninklijkeCollective\KoningGeo\Exception\NoLocationFoundException
     */
    public function get($identifier, $table): Location
    {
        $queryBuilder = $this->getQueryBuilder();
        $row = $queryBuilder->select('location', 'latitude', 'longitude')
            ->from(Location::TABLE)
            ->where($queryBuilder->expr()->eq('uid_foreign', (int)$identifier))
            ->andWhere($queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table)))
            ->execute()->fetch();
        if (is_array($row)) {
            return Location::create($row);
        }
        throw new Exception\NoLocationFoundException(
            'No record found with ID (' . $identifier . ') within table (' . $table . ')',
            1534422040815
        );
    }

    /**
     * Delete object from database
     *
     * @param integer $identifier
     * @param string $table
     * @return boolean
     */
    public function delete($identifier, $table): bool
    {
        $queryBuilder = $this->getQueryBuilder();
        return (bool)$queryBuilder
            ->delete(Location::TABLE)
            ->where($queryBuilder->expr()->eq('uid_foreign', (int)$identifier))
            ->andWhere($queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table)))
            ->execute();
    }

    /**
     * Store object in database
     *
     * @param \KoninklijkeCollective\KoningGeo\Domain\Model\Location $location
     * @return boolean
     */
    public function create(Location $location): bool
    {
        $queryBuilder = $this->getQueryBuilder();
        return (bool)$queryBuilder
            ->insert(Location::TABLE)
            ->values($location->values())
            ->execute();
    }

    /**
     * @return \TYPO3\CMS\Core\Database\Query\QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(Location::TABLE);
    }
}
