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
    'register' => [
        'path' => './assets/js/register.js',
        'entrypoint' => true,
    ],
    'dashbordStyle' => [
        'path' => './assets/js/dashbordStyle.js',
        'entrypoint' => true,
    ],
    'verifyProfile' => [
        'path' => './assets/js/verifyProfile.js',
        'entrypoint' => true,
    ],
    'profileVerification' => [
        'path' => './assets/js/profileVerification.js',
        'entrypoint' => true,
    ],
    'editProfile' => [
        'path' => './assets/js/editProfile.js',
        'entrypoint' => true,
    ],
    'vehicleNew' => [
        'path' => './assets/js/vehicleNew.js',
        'entrypoint' => true,
    ],
    'CarModelsSelection' => [
        'path' => './assets/js/CarModelsSelection.js',
        'entrypoint' => true,
    ],
    'search-input' => [
        'path' => './assets/js/address-input.js',
        'entrypoint' => true,
    ],
    'country-input' => [
        'path' => './assets/js/country-input.js',
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
