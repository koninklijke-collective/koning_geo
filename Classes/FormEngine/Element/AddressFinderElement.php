<?php

namespace KoninklijkeCollective\KoningGeo\FormEngine\Element;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use KoninklijkeCollective\KoningGeo\Domain\Repository\LocationRepository;
use KoninklijkeCollective\KoningGeo\Exception\NoLocationFoundException;
use KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * Custom Address Finder popup wizard
 */
class AddressFinderElement extends AbstractFormElement
{
    /**
     * @return array
     * @throws \KoninklijkeCollective\KoningGeo\Exception\NoLocationFoundException
     */
    public function render(): array
    {
        $resultArray = $this->initializeResultArray();
        $resultArray['requireJsModules'][] = 'TYPO3/CMS/KoningGeo/FindAddress';

        $fieldWizardResult = $this->renderFieldWizard();
        $fieldWizardHtml = $fieldWizardResult['html'];
        $resultArray = $this->mergeChildReturnIntoExistingResult($resultArray, $fieldWizardResult, false);
        $parameterArray = $this->data['parameterArray'];

        $mainFieldHtml = [];
        $mainFieldHtml[] = '<div class="koning-geo find-address-container form-control-wrap">';
        $mainFieldHtml[] = $this->getResultContainer();
        $mainFieldHtml[] = '  <div class="form-wizards-wrap">';
        $mainFieldHtml[] = '    <div class="form-wizards-element">';
        $mainFieldHtml[] = $this->getSuggestInput();
        $mainFieldHtml[] = '      <input type="hidden" name="' . $parameterArray['itemFormElName'] . '" value="' . htmlspecialchars($itemValue) . '" />';
        $mainFieldHtml[] = '    </div>';
        $mainFieldHtml[] = '    <div class="form-wizards-items-bottom">';
        $mainFieldHtml[] = $fieldWizardHtml;
        $mainFieldHtml[] = '    </div>';
        $mainFieldHtml[] = '  </div>';
        $mainFieldHtml[] = '</div>';

        $resultArray['html'] = implode(LF, $mainFieldHtml);

        return $resultArray;
    }

    /**
     * @return string
     */
    protected function getResultContainer(): string
    {
        $icon = $this->iconFactory->getIcon('koning-geo-address-location', Icon::SIZE_LARGE)->render();
        $location = $this->getConfiguredLocation();

        $html = [];
        $html[] = '<div class="address-result callout callout-success" ' . (!$location ? 'style="display: none"' : '') . '>';
        $html[] = '  <div class="media">';
        $html[] = '    <div class="media-left">';
        $html[] = $icon;
        $html[] = '    </div>';
        $html[] = '    <div class="media-body">';
        $html[] = '       <h4 class="callout-title address-label">' . htmlspecialchars($location ? $location->getLabel() : '') . '</h4>';
        $html[] = '       <small class="callout-body address-location">';
        $html[] = '          <span class="address-latitude">' . ($location ? $location->getLatitude() : '') . '</span>, ';
        $html[] = '          <span class="address-longitude">' . ($location ? $location->getLongitude() : '') . '</span>';
        $html[] = '       </small>';
        $html[] = '    </div>';
        $html[] = '  </div>';
        $html[] = '</div>';

        return implode(LF, $html);
    }

    /**
     * @return string
     */
    protected function getSuggestInput(): string
    {
        $html = [];
        $html[] = '<div class="form-wizards-items-top">';
        $html[] = '  <div class="autocomplete">';
        $html[] = '    <div class="input-group">';
        $html[] = '      <span class="input-group-addon">';
        $html[] = $this->iconFactory->getIcon('koning-geo-search', Icon::SIZE_SMALL)->render();
        $html[] = '      </span>';
        $html[] = '      <input type="search" class="koning-geo-form-find-location form-control" placeholder="Search address" ';
        $html[] = ' data-fieldname="' . htmlspecialchars($this->data['fieldName']) . '"';
        $html[] = ' data-tablename="' . htmlspecialchars($this->data['tableName']) . '"';
        $html[] = ' data-field="' . htmlspecialchars($this->data['parameterArray']['itemFormElName']) . '"';
        $html[] = ' data-uid="' . htmlspecialchars($this->data['databaseRow']['uid']) . '" />';
        $html[] = '    </div>';
        $html[] = '  </div>';
        $html[] = '</div>';

        return implode(LF, $html);
    }

    /**
     * @return \KoninklijkeCollective\KoningGeo\Domain\Model\Location|null
     */
    protected function getConfiguredLocation(): ?Location
    {
        $foreignId = $this->data['vanillaUid'];
        if (!MathUtility::canBeInterpretedAsInteger($foreignId)) {
            return null;
        }

        $table = $this->data['tableName'];
        if (!in_array($table, ConfigurationUtility::tableList(), true)) {
            return null;
        }

        try {
            return GeneralUtility::makeInstance(LocationRepository::class)->get($foreignId, $table);
        } catch (NoLocationFoundException $e) {
            return null;
        }
    }
}
