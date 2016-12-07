<?php
namespace KoninklijkeCollective\KoningGeo\Service;

/**
 * Service: Geo
 *
 * @package KoninklijkeCollective\KoningGeo\Service
 */
class GeoService
{
    /**
     * @param $location
     * @return array|null
     */
    public static function getDataForLocation($location)
    {
        $configuration = \KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility::getConfiguration();

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($location) . '&key=' . $configuration['googleMapsApiKey'];
        $response = json_decode(\TYPO3\CMS\Core\Utility\GeneralUtility::getUrl($url), true);

        if (isset($response['results'][0])) {
            $geoData = $response['results'][0];
            return [
                'location' => $geoData['formatted_address'],
                'latitude' => $geoData['geometry']['location']['lat'],
                'longitude' => $geoData['geometry']['location']['lng'],
                'viewport_ne_latitude' => $geoData['geometry']['viewport']['northeast']['lat'],
                'viewport_ne_longitude' => $geoData['geometry']['viewport']['northeast']['lng'],
                'viewport_sw_latitude' => $geoData['geometry']['viewport']['southwest']['lat'],
                'viewport_sw_longitude' => $geoData['geometry']['viewport']['southwest']['lng'],
            ];
        }
        return null;
    }

    /**
     * @param int $uidForeign
     * @param string $tableName
     * @return array|false|null
     */
    public static function getLocationData($uidForeign, $tableName)
    {
        return self::getDatabaseConnection()->exec_SELECTgetSingleRow(
            '*',
            'tx_koninggeo_domain_model_location',
            'uid_foreign = ' . (int) $uidForeign . ' AND tablename = ' . self::getDatabaseConnection()->fullQuoteStr($tableName, 'tx_koninggeo_domain_model_location')
        );
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected static function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
