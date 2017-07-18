<?php
$params = array_merge(
    require (__DIR__ . '/../../common/config/params.php'),
    require (__DIR__ . '/../../common/config/params-local.php'),
    require (__DIR__ . '/params.php'),
    require (__DIR__ . '/params-local.php')
);

return [
    'id'                  => 'app-backend',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => ['log'],
    'modules'             => [],
    'components'          => [
        'request'      => [
            'csrfParam' => '_csrf-backend',
            //接受json 输入
            'parsers'   => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'db'           => [
            'class'    => 'yii\db\Connection',
            'dsn'      => 'mysql:host=localhost;dbname=aleave',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8',
        ],
        'user'         => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-backend', 'httpOnly' => true],
            'enableSession'   => false, //不通过session来验证，因api无状态
            'loginUrl'        => null, //显示一个HTTP 403 错误而不是跳转到登录界面
        ],
        'session'      => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'urlManager'   => [
            'enablePrettyUrl'     => true, //用rest，美化路由必需开启
            'enableStrictParsing' => true,
            'showScriptName'      => false,
            'rules'               => [
                // rest方式
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'user', //控制器名
                    // 'pluralize' => false,   //默认自动加s，false则不加
                    'extraPatterns' => [
                        // 'url' => '方法名'
                        //登录注册跳转
                        'POST login'          => 'login',
                        'GET signup-test'     => 'signup-test',

                        'PUT <id:\d+>'        => 'update',
                        'POST'                => 'create',
                        // 说明
                        'GET search/<id:\d+>' => 'search', //一个参数，参数名为id,且参数必需为数字（限制）
                        'GET a/<p1>/<p2>'     => 'a', //二个参数，参数名分别为p1，p2，参数无限制
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'department',
                    'extraPatterns' => [
                        'GET'                        => 'index',
                        'POST'                       => 'create',
                        'PUT <id:\d+>'               => 'update',
                        'GET <dep_id:\d+>/positions' => 'get-positions',

                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'position',
                    'extraPatterns' => [
                        'GET'          => 'index',
                        'GET <id:\d+>' => 'view',
                        'PUT <id:\d+>' => 'update',
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'leave',
                    'extraPatterns' => [
                        'GET'          => 'index',
                        'GET <id:\d+>' => 'view',
                        'PUT <id:\d+>' => 'update',
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'leave-log',
                    'extraPatterns' => [

                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'process',
                    'extraPatterns' => [
                        'GET me'   => 'index',
                    ],
                ],
                // 'login' => 'site/login', //普通方式：指定 路由为/web/login 即跳转到site/login

                // 'books' => 'book/options',
                // 'books/<id>' => 'book/options',
            ],
        ],
    ],
    'params'              => $params,
];
