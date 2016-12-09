<?php
namespace KoninklijkeCollective\KoningGeo\Wizards;

/**
 * Wizard: Selector field
 *
 * @package KoninklijkeCollective\KoningGeo\Wizards
 */
class SelectorField
{
    /**
     * @param array $params
     * @param \TYPO3\CMS\Backend\Form\Element\InputTextElement $inputTextElement
     * @return void
     */
    public function render(array &$params, \TYPO3\CMS\Backend\Form\Element\InputTextElement $inputTextElement)
    {
        if ((int) $params['row']['uid'] > 0) {
            $location = $this->getDatabaseConnection()->exec_SELECTgetSingleRow(
                '*',
                'tx_koninggeo_domain_model_location',
                'uid_foreign = ' . (int) $params['row']['uid'] . ' AND tablename = ' . $this->getDatabaseConnection()->fullQuoteStr($params['table'], 'tx_koninggeo_domain_model_location')
            );
            if (is_array($location)) {
                $value = htmlspecialchars($location['location']);
                $coordinates = $location['latitude'] . ', ' . $location['latitude'];

                $params['item'] = str_replace('value=""', 'value="' . $value . '"', $params['item']);
                $params['item'] = $params['item'] . $coordinates;
            }
        }
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
