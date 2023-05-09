<?php
use yii\base\Application;
return [
    'id' => 'apricod-test-app',
    // basePath (базовый путь) приложения будет каталог `micro-app`
    'basePath' => __DIR__,
    'language' => 'ru',
    'components' =>  [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'pgsql:host=db;port=5432;dbname=db',
            'username' => 'db',
            'password' => 'db',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'rules' => [
                // 'GET /v1/games.<format:json|xml>' => '/games/list',
                // студии
                'GET /v1/studios<format:(\.(json|xml))?>' => '/studios/list',
                'POST /v1/studios<format:(\.(json|xml))?>' => '/studios/add',
                'DELETE /v1/studios/<id:\d+><format:(\.(json|xml))?>' => '/studios/delete',
                'PUT /v1/studios/<id:\d+><format:(\.(json|xml))?>' => '/studios/update',
                // категории
                'GET /v1/cats<format:(\.(json|xml))?>' => '/cats/list',
                'POST /v1/cats<format:(\.(json|xml))?>' => '/cats/add',
                'DELETE /v1/cats/<id:\d+><format:(\.(json|xml))?>' => '/cats/delete',
                'PUT /v1/cats/<id:\d+><format:(\.(json|xml))?>' => '/cats/update',
                // игры
                'GET /v1/games<format:(\.(json|xml))?>' => '/games/list',
                'POST /v1/games<format:(\.(json|xml))?>' => '/games/add',
                'DELETE /v1/games/<id:\d+><format:(\.(json|xml))?>' => '/games/delete',
                'PUT /v1/games/<id:\d+><format:(\.(json|xml))?>' => '/games/update',
            ],
        ],
    ],
    'on ' . Application::EVENT_BEFORE_REQUEST => function() {
        $req = Yii::$app->request;
        Yii::$app->response->headers->add('ererw', $req->method);
        if (YII_ENV_DEV && ($req->origin || $req->method == 'OPTIONS')) {
            Yii::$app->response->headers->add('Access-Control-Allow-Origin', '*');
            Yii::$app->response->headers->add('Access-Control-Allow-Methods', 'GET, POST, DELETE, PUT');
            if ($req->method == 'OPTIONS') {
                Yii::$app->end();
            }
        }
    },
    'params' => [
        'default-format' => 'json',
    ],
    // это пространство имен где приложение будет искать все контроллеры
    // 'controllerNamespace' => 'micro\controllers',
    // установим псевдоним '@micro', чтобы включить автозагрузку классов из пространства имен 'micro'
    // 'aliases' => [
    //     '@micro' => __DIR__,
    // ],
];