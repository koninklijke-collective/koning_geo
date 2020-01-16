<?php

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'koning-geo-search',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:koning_geo/Resources/Public/Icons/koning-geo-search.svg']
);
$iconRegistry->registerIcon(
    'koning-geo-address-location',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:koning_geo/Resources/Public/Icons/koning-geo-address-location.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1579525713] = [
    'nodeName' => 'addressFinder',
    'priority' => 30,
    'class' => \KoninklijkeCollective\KoningGeo\FormEngine\Element\AddressFinderElement::class,
];
