<?php
return [
    'koning-geo-find-address' => [
        'path' => '/koning-geo/find-address',
        'target' => \KoninklijkeCollective\KoningGeo\Controller\Ajax\GoogleApiController::class . '::findLocationAction',
    ],
];
