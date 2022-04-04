<?php
return [
    'shouldNotEnqueueAssetsWhenWrongHook' => [
        'config' => [
            'hook' => 'edit.php',
        ],
        'expected' => false,
    ],
    'shouldEnqueueAssets' => [
        'config' => [
            'hook' => 'settings_page_rocketcdn',
        ],
        'expected' => true,
    ],
];
