<?php

namespace KoninklijkeCollective\KoningGeo\Utility;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;

/**
 * Utility: Geo
 *
 * @package KoninklijkeCollective\KoningGeo\Utility
 */
class GeoUtility
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
     * @return Location
     */
    public static function getLocationData($uidForeign, $tableName)
    {
        $row = self::getDatabaseConnection()->exec_SELECTgetSingleRow(
            '*',
            Location::TABLE,
            'uid_foreign = ' . (int)$uidForeign . ' AND tablename = ' . self::getDatabaseConnection()->fullQuoteStr($tableName,
                Location::TABLE)
        );
        if (is_array($row)) {
            $location = new Location();
            $location->setLocation($row['location']);
            $location->setLatitude($row['latitude']);
            $location->setLongitude($row['longitude']);
            $location->setViewportNeLatitude($row['viewport_ne_latitude']);
            $location->setViewportNeLongitude($row['viewport_ne_longitude']);
            $location->setViewportSwLatitude($row['viewport_sw_latitude']);
            $location->setViewportSwLongitude($row['viewport_sw_longitude']);
            return $location;
        }
        return null;
    }

    /**
     * @return \TYPO3\CMS\Core\Database\DatabaseConnection
     */
    protected static function getDatabaseConnection()
    {
        return $GLOBALS['TYPO3_DB'];
    }
}
