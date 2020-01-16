<?php

namespace KoninklijkeCollective\KoningGeo\Controller\Ajax;

use KoninklijkeCollective\KoningGeo\Service\Google\GeocodeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GoogleApiController
{
    /** @var \TYPO3\CMS\Core\Imaging\IconFactory */
    protected $iconFactory;

    /**
     * Used by TYPO3/CMS/KoningGeo/FindAddress AJAX interaction
     *
     * @param  \Psr\Http\Message\ServerRequestInterface  $request
     * @param  \Psr\Http\Message\ResponseInterface  $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function findLocationAction(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $address = $request->getParsedBody()['value']
            ?? $request->getQueryParams()['value']
            ?? null;
        $rows = $this->getAddressLocationsForAutocomplete($address);

        return (new JsonResponse())->setPayload(array_values($rows));
    }

    /**
     * @param  string|null  $input
     * @return array
     */
    protected function getAddressLocationsForAutocomplete(?string $input = null): array
    {
        if ($input === null) {
            return [];
        }

        $rows = [];
        foreach ($this->getGeocodeService()->search($input) as $item) {
            $label = $item['formatted_address'];
            $latitude = $item['geometry']['location']['lat'];
            $longitude = $item['geometry']['location']['lng'];

            $rows[] = [
                'text' => ' <strong class="suggest-label">' . $label . '</strong><br />'
                    . '<small class="suggest-path">' . $latitude . ', ' . $longitude . '</small>',
                'response' => $item,
                'sprite' => $this->getIconFactory()->getIcon('koning-geo-address-location', Icon::SIZE_SMALL)->render(),
            ];
        }

        return $rows;
    }

    /**
     * @return \KoninklijkeCollective\KoningGeo\Service\Google\GeocodeService
     */
    protected function getGeocodeService(): GeocodeService
    {
        return GeneralUtility::makeInstance(GeocodeService::class);
    }

    /**
     * @return \TYPO3\CMS\Core\Imaging\IconFactory
     */
    protected function getIconFactory(): IconFactory
    {
        if ($this->iconFactory === null) {
            $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        }

        return $this->iconFactory;
    }
}
