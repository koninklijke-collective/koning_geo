<?php
namespace KoninklijkeCollective\KoningGeo\Domain\Repository;

/**
 * Repository: Location
 *
 * @package KoninklijkeCollective\KoningGeo\Domain\Repository
 */
class LocationRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * @return void
     */
    public function initializeObject()
    {
        $querySettings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings::class);
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings */
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param int $uidForeign
     * @param string $tableName
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location
     */
    public function findOneByUidForeignAndTablename($uidForeign, $tableName)
    {
        $query = $this->createQuery();
        $constraints = [];
        $constraints[] = $query->equals('uidForeign', (int) $uidForeign);
        $constraints[] = $query->equals('tablename', $tableName);
        return $query->matching($query->logicalAnd($constraints))->execute()->getFirst();
    }
}
