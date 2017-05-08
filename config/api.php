<?php
$config = [
    'components' => [
        'request' => [
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' =>  \yii\web\Response::FORMAT_JSON,
            'class' => 'yii\web\Response',
        ],
        'user' => [
            'identityClass'   => 'app\models\UserIdentity',
            'enableAutoLogin' => false,
            'enableSession'   => false,
            'loginUrl'        =>null,
        ],
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'api/v1_0/user',
                        'api/v1_0/authentication',
                        'api/v1_0/session',
                        'api/v1_0/verification',
                        'api/v1_0/platform',
                        'api/v1_0/project',
                        'api/v1_0/investment',
                        'api/v1_0/notice',
                        'api/v1_0/message',
                        'api/v1_0/staff',
                        'api/v1_0/platform-comment',
                        'api/v1_0/withdrawal',
                    ],
                    'tokens'=>[
                        '{id}'    => '<id:\\d[\\d,]*>', //default
                        '{uid}'   => '<uid:[\\w-]{32}>',
                        '{token}' => '<token:[\\w-]{128}>',
                    ],
                    'extraPatterns'=>[
                        'PUT,PATCH {uid}'                  => 'update',
                        'GET,HEAD {uid}'                   => 'view',
                        'GET,HEAD {id}/projects'           => 'projects',
                        'GET,HEAD {uid}/investments'       => 'investments',
                        'GET,HEAD {id}/investments'        => 'investments',
                        'GET,HEAD {uid}/withdrawals'       => 'withdrawals',
                        'GET,HEAD {id}/withdrawals'        => 'withdrawals',
                        'GET,HEAD {uid}/platform-comments' => 'platform-comments',
                        'GET,HEAD received'                => 'received',
                        'GET,HEAD sent'                    => 'sent',
                        'GET,HEAD {id}/staff'              => 'staff',
                        'GET,HEAD {id}/comments'           => 'comments',
                        'GET,HEAD platform-comments'       => 'platform-comments',
                        'GET,HEAD {id}/replies'            => 'replies',
                        'POST {id}/vote'                   => 'vote',
                    ]
                ],
            ],
        ]
    ],
];

return $config;
