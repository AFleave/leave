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
        //设置返回格式无论如何都为json
        'response'     => [
            'class'         => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                $code     = $response->getStatusCode();
                $msg      = $response->statusText;
                if ($code == 404) {
                    !empty($response->data['message']) && $msg = $response->data['message'];
                }
                $data = [
                    'code' => $code,
                    'msg'  => $msg,
                ];
                $code == 200 && $data['data'] = $response->data;
                $response->data               = $data;
                $response->format             = yii\web\Response::FORMAT_JSON;
            },
        ],
        'db'           => [
            'class'    => 'yii\db\Connection',
            'dsn'      => 'mysql:host=localhost;dbname=api',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8',
        ],
        'user'         => [
            'identityClass'   => 'backend\models\User',
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
        // 'errorHandler' => [
        //     'errorAction' => 'user/error',
        // ],

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
                        'POST signup'          => 'signup',
                        'GET signup-test'     => 'signup-test',
                        //'GET view' => 'view',
                        // 说明
                        'GET search/<id:\d+>' => 'search', //一个参数，参数名为id,且参数必需为数字（限制）
                        'GET a/<p1>/<p2>'     => 'a', //二个参数，参数名分别为p1，p2，参数无限制
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'department',
                    'extraPatterns' => [
                        'GET <dep_id:\d+>/positions' => 'get-positions',
                        'GET positions'              => 'get-all-positions',
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'position',
                    'extraPatterns' => [
                        'GET index' => 'index',
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'leave',
                    'extraPatterns' => [
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'leave-log',
                    'extraPatterns' => [
                        'GET index' => 'index',
                        'GET alllog/<id:\d+>'=> 'alllog',
                    ],
                ],
                [
                    'class'         => 'yii\rest\UrlRule',
                    'controller'    => 'process',
                    'extraPatterns' => [
                        'GET index' => 'index',
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
