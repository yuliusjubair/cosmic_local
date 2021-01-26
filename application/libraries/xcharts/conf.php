<?php

/**
 * Paths and names for the javascript libraries needed by higcharts/highstock charts
 */
$jsFiles = array(
    'jQuery' => array(
        'name' => 'jquery.min.js',
        'path' => '../js/'),
	'exportimage' => array(
        'name' => 'exporting.js',
        'path' => '../js/modules/'),
    'mootools' => array(
        'name' => 'mootools-yui-compressed.js',
        'path' => 'https://ajax.googleapis.com/ajax/libs/mootools/1.4.5/'),

    'prototype' => array(
        'name' => 'prototype.js',
        'path' => 'https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/'),

    'highcharts' => array(
        'name' => 'highcharts.js',
        'path' => '../js/'),

    'highchartsMootoolsAdapter' => array(
        'name' => 'mootools-adapter.js',
        'path' => 'http://code.highcharts.com/adapters/'),

    'highchartsPrototypeAdapter' => array(
        'name' => 'prototype-adapter.js',
        'path' => 'http://code.highcharts.com/adapters/'),

    'highstock' => array(
        'name' => 'highstock.js',
        'path' => 'http://code.highcharts.com/stock/'),

    'highstockMootoolsAdapter' => array(
        'name' => 'mootools-adapter.js',
        'path' => 'http://code.highcharts.com/stock/adapters/'),

    'highstockPrototypeAdapter' => array(
        'name' => 'prototype-adapter.js',
        'path' => 'http://code.highcharts.com/stock/adapters/'),
    //Extra scripts used by Highcharts 3.0 charts
    'extra' => array(
        'highcharts-more' => array(
            'name' => 'highcharts-more.js',
            'path' => '../js/'
        ),
        'exporting' => array(
            'name' => 'exporting.js',
            'path' => '../js/modules/'
        ),
    )
);