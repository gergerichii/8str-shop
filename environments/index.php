<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'shop_8str/runtime',
            'shop_8str/web/assets',
            'sputnik/runtime',
            'sputnik/web/assets',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'common/config/web/local/main.php',
        ],
        // В вагранте, вместо ссылок, будут созданы папки, и к ним подмонтированы исходные папки.
        // Т.к. В линухе подмонтирована виндусовская файловая система, и линки в ней не создаются
        'createSymlink' => [
            'admin/web/files' => 'common/webFiles',
            'frontend/web/files' => 'common/webFiles',
            'shop_8str/web/files' => 'common/webFiles',
            'shop_8str/web/images' => 'frontend/web/images',
            'sputnik/web/files' => 'common/webFiles',
            'sputnik/web/images' => 'frontend/web/images',
        ]
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'admin/runtime',
            'admin/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'shop_8str/runtime',
            'shop_8str/web/assets',
            'sputnik/runtime',
            'sputnik/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'common/config/web/local/main.php',
        ],
        'createSymlink' => [
            'admin/web/files' => 'common/webFiles',
            'frontend/web/files' => 'common/webFiles',
            'shop_8str/web/files' => 'common/webFiles',
            'sputnik/web/files' => 'common/webFiles',
            '../sputnikvideo.ru/public_html' => 'sputnik/web',
            '../8str.8str.ru/public_html' => 'shop_8str/web',
            '../test.8str.ru/public_html' => 'frontend/web',
            '../admin.8str.ru/public_html' => 'admin/web',
            '../admin.sputnikvideo.ru/public_html' => 'admin/web',
            'admin/web/.htaccess' => 'environments/htaccess',
            'frontend/web/.htaccess' => 'environments/htaccess',
            'shop_8str/web/.htaccess' => 'environments/htaccess',
            'sputnik/web/.htaccess' => 'environments/htaccess',
            'shop_8str/web/images' => 'frontend/web/images',
            'sputnik/web/images' => 'frontend/web/images',
        ]
    ],
];
