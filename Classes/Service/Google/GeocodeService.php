<?php

namespace KoninklijkeCollective\KoningGeo\Service\Google;

use KoninklijkeCollective\KoningGeo\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GeocodeService
{
    public const GOOGLE_MAPS_GEOCODE_API = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * @param  string  $address
     * @return array
     */
    public function search(string $address): array
    {
        $data = GeneralUtility::getUrl($this->url(['address' => $address]));
        $response = json_decode($data, true);

        return $response['results'] ?? [];
    }

    /**
     * @param  string  $placeId
     * @return array|null
     */
    protected function get(string $placeId): ?array
    {
        $data = GeneralUtility::getUrl($this->url(['place_id' => $placeId]));
        $response = json_decode($data, true);

        return $response['results'][0] ?? [];
    }

    /**
     * @param  array  $parameters
     * @return string
     */
    protected function url(array $parameters = []): string
    {
        $parameters['key'] = ConfigurationUtility::googleMapsApiKey();

        return self::GOOGLE_MAPS_GEOCODE_API . '?' . http_build_query($parameters);
    }
}
