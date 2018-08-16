<?php

namespace KoninklijkeCollective\KoningGeo\Form\Element;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use TYPO3\CMS\Backend\Form\Element\InputTextElement;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Override value for SelectorKoningGeo
 * @package KoninklijkeCollective\KoningGeo\FormEngine\FieldInformation
 */
class SelectorKoningGeo extends InputTextElement
{

    /**
     * Handler for single nodes
     *
     * @return array As defined in initializeResultArray() of AbstractNode
     */
    public function render(): array
    {

        $uid = $this->data['vanillaUid'];
        $table = $this->data['tableName'];
        if ((int)$uid > 0 && MathUtility::canBeInterpretedAsInteger($uid)) {
            $location = $this->getOriginalValue($uid, $table);
            if (!empty($location)) {
                $this->data['parameterArray']['itemFormElValue'] = implode(', ', $location);
            }
        }

        return parent::render();
    }

    /**
     * @param integer $uid
     * @param string $table
     * @return array
     */
    protected function getOriginalValue($uid, $table): array
    {
        if ((int)$uid > 0) {
            $queryBuilder = $this->getQueryBuilder();
            $location = $queryBuilder->select('location', 'latitude', 'longitude')->from(Location::TABLE)
                ->where($queryBuilder->expr()->eq('uid_foreign', (int)$uid))
                ->andWhere($queryBuilder->expr()->eq('tablename', $queryBuilder->createNamedParameter($table)))
                ->execute()->fetch();
            if (is_array($location)) {
                return [
                    htmlspecialchars($location['location']),
                    $location['latitude'] . ', ' . $location['longitude']
                ];
            }
        }
        return [];
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(Location::TABLE);
    }
}
