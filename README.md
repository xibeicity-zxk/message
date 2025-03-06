# Message

一个统一的消息通知系统，支持ThinkPHP和Laravel框架，可以方便地发送短信、钉钉、微信等多种消息。

## 环境要求

- PHP >= 7.2
- ThinkPHP >= 6.0 或 Laravel >= 8.0

## 安装

通过Composer安装：

```bash
composer require xibeicity/message
```

## 配置

### ThinkPHP

1. 复制配置文件到项目的config目录：

```bash
cp vendor/xibeicity/message/config/message.php config/message.php
```

2. 在.env文件中配置相关参数：

```env
# 默认消息驱动
MESSAGE_DRIVER=sms

# 阿里云短信配置
ALIYUN_ACCESS_KEY_ID=your-access-key-id
ALIYUN_ACCESS_KEY_SECRET=your-access-key-secret
ALIYUN_SMS_SIGN_NAME=your-sign-name
ALIYUN_SMS_TEMPLATE_CODE=your-template-code

# 钉钉配置
DINGTALK_APP_KEY=your-app-key
DINGTALK_APP_SECRET=your-app-secret
DINGTALK_AGENT_ID=your-agent-id

# 钉钉群机器人配置
DINGTALK_ROBOT_ACCESS_TOKEN=your-access-token
DINGTALK_ROBOT_SECRET=your-secret

# 微信公众号配置
WECHAT_OFFICIAL_ACCOUNT_APP_ID=your-app-id
WECHAT_OFFICIAL_ACCOUNT_APP_SECRET=your-app-secret
WECHAT_OFFICIAL_ACCOUNT_TOKEN=your-token
WECHAT_OFFICIAL_ACCOUNT_AES_KEY=your-aes-key

# 微信小程序配置
WECHAT_MINI_PROGRAM_APP_ID=your-app-id
WECHAT_MINI_PROGRAM_APP_SECRET=your-app-secret

# 微信群机器人配置
WECHAT_ROBOT_KEY=your-robot-key
```

3. 执行数据库迁移（用于消息记录功能）：

```bash
php think migrate:run
```

### Laravel

1. 发布配置文件：

```bash
php artisan vendor:publish --provider="Xibeicity\Message\Laravel\MessageServiceProvider"
```

2. 在.env文件中配置相关参数（同ThinkPHP配置）

3. 执行数据库迁移：

```bash
php artisan migrate
```

## 基础使用

### 发送短信

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// 发送短信
$message->driver('sms')->send(['13800138000'], '您的验证码是：123456');

// 使用模板发送短信
$message->driver('sms')->send(['13800138000'], '', [
    'template_code' => 'SMS_123456789',
    'template_param' => [
        'code' => '123456'
    ]
]);
```

### 发送钉钉工作通知

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// 发送钉钉消息
$message->driver('dingtalk')->send(['user123'], '这是一条测试消息');

// 发送带链接的消息
$message->driver('dingtalk')->send(['user123'], '请查看详情', [
    'link' => 'https://example.com',
    'title' => '通知标题'
]);
```

### 发送钉钉群机器人消息

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// 发送文本消息
$message->driver('dingtalk_robot')->send(['@all'], '这是一条测试消息');

// 发送markdown消息
$message->driver('dingtalk_robot')->send(['@all'], '# 标题\n内容', [
    'msg_type' => 'markdown'
]);
```

### 发送微信公众号模板消息

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// 发送模板消息
$message->driver('wechat_official_account')->send(['OPENID'], '', [
    'template_id' => 'template-id',
    'url' => 'https://example.com',
    'data' => [
        'first' => ['value' => '您有一条新通知'],
        'keyword1' => ['value' => '通知内容'],
        'remark' => ['value' => '请及时查看']
    ]
]);
```

### 发送微信小程序订阅消息

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// 发送订阅消息
$message->driver('wechat_mini_program')->send(['OPENID'], '', [
    'template_id' => 'template-id',
    'page' => 'pages/index/index',
    'data' => [
        'thing1' => ['value' => '商品名称'],
        'amount2' => ['value' => '89.90'],
        'date3' => ['value' => '2024-01-01 12:00:00']
    ]
]);
```

### 发送微信群机器人消息

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// 发送文本消息
$message->driver('wechat_robot')->send(['@all'], '这是一条测试消息');

// 发送markdown消息
$message->driver('wechat_robot')->send(['@all'], '# 标题\n内容', [
    'msg_type' => 'markdown'
]);
```

## 消息列表功能

### Web界面

访问消息列表页面：

- ThinkPHP: `http://your-domain/message/list`
- Laravel: `http://your-domain/message/list`

支持按以下条件筛选消息：
- 消息驱动（短信/钉钉/钉钉群机器人/微信公众号/微信小程序/微信群机器人）
- 发送状态（待发送/成功/失败）
- 接收者

### API接口

获取消息列表：

```http
GET /message/list

请求参数：
- driver: 消息驱动（可选，sms/dingtalk/dingtalk_robot/wechat_official_account/wechat_mini_program/wechat_robot）
- status: 发送状态（可选，pending/success/failed）
- to: 接收者（可选）
- per_page: 每页数量（可选，默认15）
- page: 页码（可选，默认1）

响应示例：
{
    "code": 0,
    "message": "success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "driver": "wechat_official_account",
                "to": "OPENID",
                "content": "模板消息内容",
                "status": "success",
                "created_at": "2024-01-01 12:00:00"
            }
        ],
        "total": 1,
        "per_page": 15
    }
}
```

## 高级特性

### 自定义消息驱动

1. 创建自定义驱动类，实现MessageInterface接口：

```php
use Xibeicity\Message\Contracts\MessageInterface;

class CustomDriver implements MessageInterface
{
    public function send(array $to, string $content, array $options = []): bool
    {
        // 实现发送逻辑
        return true;
    }

    public function getStatus(): array
    {
        // 返回发送状态
        return [
            'success' => true,
            'message_id' => 'xxx'
        ];
    }

    public function getError(): ?string
    {
        // 返回错误信息
        return null;
    }
}
```

2. 注册自定义驱动：

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();
$message->extend('custom', function () {
    return new CustomDriver();
});

// 使用自定义驱动发送消息
$message->driver('custom')->send(['user123'], '这是一条测试消息');
```
