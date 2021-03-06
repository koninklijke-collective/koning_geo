<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Koning: Geo',
    'description' => 'Utility to retrieve and save geo data',
    'category' => 'misc',
    'version' => '2.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper',
    'author_email' => 'jesper@koninklijk.io',
    'author_company' => 'Koninklijke Collective',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'KoninklijkeCollective\\KoningGeo\\' => 'Classes',
        ],
    ],
];
