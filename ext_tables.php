<?php
call_user_func(function ($extension) {
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][$extension] = \KoninklijkeCollective\KoningGeo\Hooks\ProcessData::class;
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][$extension] = \KoninklijkeCollective\KoningGeo\Hooks\ProcessData::class;
}, 'koning_geo');
