<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],

// STYLES
    'registerStyle' => [
        'path' => './assets/js/styles/registerStyle.js',
        'entrypoint' => true,
    ],
    'verifyProfileStyle' => [
        'path' => './assets/js/styles/verifyProfileStyle.js',
        'entrypoint' => true,
    ],
    'editProfileStyle' => [
        'path' => './assets/js/styles/editProfileStyle.js',
        'entrypoint' => true,
    ],
    'adminDashboardStyle' => [
        'path' => './assets/js/styles/adminDashboardStyle.js',
        'entrypoint' => true,
    ],
    'componentsStyle' => [
        'path' => './assets/js/styles/componentsStyle.js',
        'entrypoint' => true,
    ],
    'userListStyle' => [
        'path' => './assets/js/styles/userListStyle.js',
        'entrypoint' => true,
    ],
    

// JAVASCRIPTS
    'profileVerification' => [
        'path' => './assets/js/profileVerification.js',
        'entrypoint' => true,
    ],

    'vehicleNew' => [
        'path' => './assets/js/vehicleNew.js',
        'entrypoint' => true,
    ],
    'vehiclePhotoDynamicInput' => [
        'path' => './assets/js/vehiclePhotoDynamicInput.js',
        'entrypoint' => true,
    ],
    


    // API
    'CarModelsSelection' => [
        'path' => './assets/js/api/CarModelsSelection.js',
        'entrypoint' => true,
    ],
    'search-input' => [
        'path' => './assets/js/api/address-input.js',
        'entrypoint' => true,
    ],
    'vehicles-search-input' => [
        'path' => './assets/js/api/vehicles-search-input.js',
        'entrypoint' => true,
    ],
    'country-input' => [
        'path' => './assets/js/api/country-input.js',
        'entrypoint' => true,
    ],
    'geolocation' => [
        'path' => './assets/js/api/geolocation.js',
        'entrypoint' => true,
    ],
   




    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => './vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@hotwired/turbo' => [
        'version' => '7.3.0',
    ],
];
