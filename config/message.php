<?php

return [
    // 默认消息驱动
    'default' => env('MESSAGE_DRIVER', 'sms'),

    // 短信配置
    'sms' => [
        'driver' => 'aliyun',
        'access_key_id' => env('ALIYUN_ACCESS_KEY_ID'),
        'access_key_secret' => env('ALIYUN_ACCESS_KEY_SECRET'),
        'sign_name' => env('ALIYUN_SMS_SIGN_NAME'),
        'template_code' => env('ALIYUN_SMS_TEMPLATE_CODE'),
    ],

    // 钉钉配置
    'dingtalk' => [
        'app_key' => env('DINGTALK_APP_KEY'),
        'app_secret' => env('DINGTALK_APP_SECRET'),
        'agent_id' => env('DINGTALK_AGENT_ID'),
    ],

    // 钉钉群机器人配置
    'dingtalk_robot' => [
        'webhook' => env('DINGTALK_ROBOT_WEBHOOK'),
        'secret' => env('DINGTALK_ROBOT_SECRET'),
    ],

    // 微信公众号配置
    'wechat_official_account' => [
        'app_id' => env('WECHAT_OA_APP_ID'),
        'app_secret' => env('WECHAT_OA_APP_SECRET'),
        'token' => env('WECHAT_OA_TOKEN'),
        'aes_key' => env('WECHAT_OA_AES_KEY'),
    ],

    // 微信小程序配置
    'wechat_mini_program' => [
        'app_id' => env('WECHAT_MP_APP_ID'),
        'app_secret' => env('WECHAT_MP_APP_SECRET'),
        'templates' => [
            // 示例模板配置
            'verify_code' => [
                'template_id' => env('WECHAT_MP_VERIFY_CODE_TEMPLATE_ID'),
                'page' => 'pages/index/index',
                'data' => [
                    'thing1' => '验证码',
                    'thing2' => '登录验证'
                ]
            ],
            'order_status' => [
                'template_id' => env('WECHAT_MP_ORDER_STATUS_TEMPLATE_ID'),
                'page' => 'pages/order/detail',
                'data' => [
                    'thing1' => '订单状态更新',
                    'thing2' => '订单详情'
                ]
            ],
            // 可以添加更多模板配置
        ],
    ],

    // 微信群机器人配置
    'wechat_robot' => [
        'webhook' => env('WECHAT_ROBOT_WEBHOOK'),
    ],

    // 数据库记录配置
    'database' => [
        'enabled' => env('MESSAGE_LOG_ENABLED', true),
        'connection' => env('MESSAGE_LOG_CONNECTION', ''),
        'table' => env('MESSAGE_LOG_TABLE', 'message_logs'),
    ],

    // 其他消息驱动配置可以在这里添加
];