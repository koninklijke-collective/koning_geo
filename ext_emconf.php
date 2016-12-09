<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Koning: Geo',
    'description' => 'Utility to retrieve and save geo data',
    'category' => 'misc',
    'version' => '1.0.0',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'author' => 'Jesper Paardekooper',
    'author_email' => 'jesper@koninklijk.io',
    'author_company' => 'Koninklijke Collective',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'KoninklijkeCollective\\KoningGeo\\' => 'Classes'
        ]
    ],
];
