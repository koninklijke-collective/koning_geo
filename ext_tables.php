<?php
call_user_func(function($extensionKey) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$extensionKey] = \KoninklijkeCollective\KoningGeo\Hooks\ProcessData::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extensionKey] = \KoninklijkeCollective\KoningGeo\Hooks\ProcessData::class;
}, 'koning_geo');
