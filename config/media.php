<?php

/*
|-----------------------------------------------------------------------
| Config custom per stabilire estensioni ammesse  dei media del progetto
|
*/

return [

    'images' => [
        'extensions' => ['jpg', 'jpeg', 'png', 'webp', 'avif'],
        'mime_types' => [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/avif',
        ],

        'max_size' => 2048,
    ],

];
