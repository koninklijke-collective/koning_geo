<?php

namespace KoninklijkeCollective\KoningGeo\Form\Element;

use KoninklijkeCollective\KoningGeo\Exception\AbstractException;
use KoninklijkeCollective\KoningGeo\Service\LocationService;
use TYPO3\CMS\Backend\Form\Element\InputTextElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Override value for SelectorKoningGeo
 *
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
            try {
                $location = $this->getLocationService()->get($uid, $table);

                $this->data['parameterArray']['itemFormElValue'] = implode(', ', [
                    htmlspecialchars($location->getLocation()),
                    $location->getLatitude(),
                    $location->getLongitude(),
                ]);
            } catch (AbstractException $e) {
                // Never throw exception when caused by this extension
            }
        }

        return parent::render();
    }

    /**
     * @return LocationService
     */
    protected function getLocationService(): LocationService
    {
        return GeneralUtility::makeInstance(LocationService::class);
    }
}
