<?php

namespace KoninklijkeCollective\KoningGeo\Utility;

use KoninklijkeCollective\KoningGeo\Domain\Model\Location;
use KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
        $configuration = ConfigurationUtility::getConfiguration();

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($location) . '&key=' . $configuration['googleMapsApiKey'];
        $response = json_decode(GeneralUtility::getUrl($url), true);

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
}
