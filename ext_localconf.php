<?php
call_user_func(function ($extension) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1534414341664] = [
        'nodeName' => 'selectorKoningGeo',
        'priority' => 30,
        'class' => \KoninklijkeCollective\KoningGeo\Form\Element\SelectorKoningGeo::class,
    ];
}, 'koning_geo');