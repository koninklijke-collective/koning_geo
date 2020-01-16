<?php

namespace KoninklijkeCollective\KoningGeo\Domain\Repository;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use KoninklijkeCollective\KoningGeo\Exception\NoLocationFoundException;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LocationRepository
{
    /**
     * Check if record exists in database
     *
     * @param  int  $foreignId
     * @param  string  $table
     * @return bool
     */
    public function exists(int $foreignId, string $table): bool
    {
        $queryBuilder = $this->getQueryBuilder();

        return $queryBuilder
                ->count('place_id')
                ->from(Location::TABLE)
                ->where($queryBuilder->expr()->eq('uid_foreign', $foreignId))
                ->andWhere($queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table)))
                ->execute()->fetchColumn() === 1;
    }

    /**
     * Retrieve location object from database
     *
     * @param  int  $foreignId
     * @param  string  $table
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     * @throws \KoninklijkeCollective\KoningGeo\Exception\NoLocationFoundException
     */
    public function get(int $foreignId, string $table): Location
    {
        $queryBuilder = $this->getQueryBuilder();
        $row = $queryBuilder->select('uid_foreign', 'tablename', 'label', 'place_id', 'latitude', 'longitude', 'response')
            ->from(Location::TABLE)
            ->where($queryBuilder->expr()->eq('uid_foreign', $foreignId))
            ->andWhere($queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table)))
            ->execute()->fetch();
        if (is_array($row)) {
            return Location::create($row);
        }
        throw NoLocationFoundException::invalidRecord($foreignId, $table);
    }

    /**
     * Delete object from database
     *
     * @param  \KoninklijkeCollective\KoningGeo\Domain\Model\Location  $location
     * @return bool
     */
    public function createOrUpdate(Location $location): bool
    {
        if ($this->exists($location->getUidForeign(), $location->getTablename())) {
            return $this->update($location);
        }

        return $this->create($location);
    }

    /**
     * Delete object from database
     *
     * @param  int  $foreignId
     * @param  string  $table
     * @return bool
     */
    public function delete(int $foreignId, string $table): bool
    {
        $queryBuilder = $this->getQueryBuilder();

        return (bool)$queryBuilder
            ->delete(Location::TABLE)
            ->where($queryBuilder->expr()->eq('uid_foreign', $foreignId))
            ->andWhere($queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table)))
            ->execute();
    }

    /**
     * Store object in database
     *
     * @param  \KoninklijkeCollective\KoningGeo\Domain\Model\Location  $location
     * @return bool
     */
    public function create(Location $location): bool
    {
        return (bool)$this->getQueryBuilder()
            ->insert(Location::TABLE)
            ->values($location->values())
            ->execute();
    }

    /**
     * Update object in database
     *
     * @param  \KoninklijkeCollective\KoningGeo\Domain\Model\Location  $location
     * @return bool
     */
    public function update(Location $location): bool
    {
        $queryBuilder = $this->getQueryBuilder();
        $query = $queryBuilder
            ->update(Location::TABLE)
            ->where($queryBuilder->expr()->eq('uid_foreign', $location->getUidForeign()))
            ->andWhere($queryBuilder->expr()
                ->eq('tablename', $queryBuilder->createNamedParameter($location->getTablename())));
        foreach ($location->values() as $column => $value) {
            $query->set($column, $value);
        }

        return (bool)$query->execute();
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
