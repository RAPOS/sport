<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name'  => 'FindTeam.Net',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'geoData'
    ],
    'language' => 'ru',
    'timezone' => 'UTC',
    'controllerMap' => [
        'auth' => [
            'class'         => 'phpnt\oAuth\controllers\AuthController',
            'modelUser'     => 'app\models\User'  // путь к модели User
        ],
        'images' => [
            'class'         => 'phpnt\cropper\controllers\ImagesController',
        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    // https://developers.facebook.com/apps
                    'class'         => 'phpnt\oAuth\oauth\Facebook',
                    'email'         => 'email',
                    'first_name'    => 'first_name',
                    'last_name'     => 'last_name',
                    'avatar'        => 'avatar',
                    'gender'        => 'sex',
                    'female'        => 2,
                    'male'          => 1,
                    'status'        => 'status',
                    'statusActive'  => 1,
                    'clientId'      => '893194340785047',
                    'clientSecret'  => 'b96c2f131dca7bf2bc5cef11bb2402fd',
                ],
                'vkontakte' => [
                    // https://vk.com/editapp?act=create
                    'class'         => 'phpnt\oAuth\oauth\VKontakte',
                    'email'         => 'email',
                    'first_name'    => 'first_name',
                    'last_name'     => 'last_name',
                    'avatar'        => 'avatar',
                    'gender'        => 'sex',
                    'female'        => 2,
                    'male'          => 1,
                    'status'        => 'status',
                    'statusActive'  => 1,
                    'clientId'      => '5533275',
                    'clientSecret'  => '3gqNBiJaZWlOPLAFkQhO',
                ],
            ]
        ],
        'language' => 'ru-RU',
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => 'app/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'geoData' => [
            'class'             => 'phpnt\geoData\GeoData',         // путь к классу
            'addToCookie'       => true,                            // сохранить в куки
            'addToSession'      => true,                            // сохранить в сессии
            'setTimezoneApp'    => true,                            // установить timezone в formatter (для вывода)
            'cookieDuration'    => 2592000                          // время хранения в сессии
        ],
        'request' => [
            //'class' => 'app\components\LangRequest',
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TXwFXpYCPqzCXvLyzvDC9HJDVFJBfXrNS7gJoEdqSnJ6TRealzgKirp',
            'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
        ],
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'languages' => ['ru', 'en'],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'pattern' => '',
                    'route' => 'site/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'auth/index',
                    'route' => 'auth/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => 'image/get/<id>/<width>/<height>/<type>',
                    'route' => 'image/get',
                ],
                [
                    'pattern' => '<controller>/<action>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<controller>/<action>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>/<id:\d+>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
            ]
        ],
        'eauth' => array(
            'class' => 'nodge\eauth\EAuth',
            'popup' => true, // Use the popup window instead of redirecting.
            'cache' => false, // Cache component name or false to disable cache. Defaults to 'cache' on production environments.
            'cacheExpire' => 0, // Cache lifetime. Defaults to 0 - means unlimited.
//			'httpClient' => array(
            // uncomment this to use streams in safe_mode
            //'useStreamsFallback' => true,
//			),
//			'tokenStorage' => array(
//				'class' => '@app\eauth\DatabaseTokenStorage',
//			),
            'services' => array(
                'facebook' => array(
                    // register your app here: https://developers.facebook.com/apps/
                    'class' => 'nodge\eauth\services\FacebookOAuth2Service',
                    'clientId' => '1679906032274458',
                    'clientSecret' => 'e70daeb7237afddacb69694f03ce9bd0',
                    'scope' => 'user_friends'
                ),
                'vkontakte' => array(
                    // register your app here: https://vk.com/editapp?act=create&site=1
                    'class' => 'nodge\eauth\services\VKontakteOAuth2Service',
                    'clientId' => '5243976',
                    'clientSecret' => 'ZpFHbvhKUIWpkOVpMDPK',
                )
            )
        ),
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
