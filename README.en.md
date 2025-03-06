# Message

A unified message notification system that supports ThinkPHP and Laravel frameworks, enabling convenient sending of SMS, DingTalk, WeChat, and other types of messages.

## Requirements

- PHP >= 7.2
- ThinkPHP >= 6.0 or Laravel >= 8.0

## Installation

Install via Composer:

```bash
composer require xibeicity/message
```

## Configuration

### ThinkPHP

1. Copy the configuration file to your project's config directory:

```bash
cp vendor/xibeicity/message/config/message.php config/message.php
```

2. Configure the related parameters in your .env file:

```env
# Default message driver
MESSAGE_DRIVER=sms

# Aliyun SMS configuration
ALIYUN_ACCESS_KEY_ID=your-access-key-id
ALIYUN_ACCESS_KEY_SECRET=your-access-key-secret
ALIYUN_SMS_SIGN_NAME=your-sign-name
ALIYUN_SMS_TEMPLATE_CODE=your-template-code

# DingTalk configuration
DINGTALK_APP_KEY=your-app-key
DINGTALK_APP_SECRET=your-app-secret
DINGTALK_AGENT_ID=your-agent-id

# DingTalk Robot configuration
DINGTALK_ROBOT_ACCESS_TOKEN=your-access-token
DINGTALK_ROBOT_SECRET=your-secret

# WeChat Official Account configuration
WECHAT_OFFICIAL_ACCOUNT_APP_ID=your-app-id
WECHAT_OFFICIAL_ACCOUNT_APP_SECRET=your-app-secret
WECHAT_OFFICIAL_ACCOUNT_TOKEN=your-token
WECHAT_OFFICIAL_ACCOUNT_AES_KEY=your-aes-key

# WeChat Mini Program configuration
WECHAT_MINI_PROGRAM_APP_ID=your-app-id
WECHAT_MINI_PROGRAM_APP_SECRET=your-app-secret

# WeChat Robot configuration
WECHAT_ROBOT_KEY=your-robot-key
```

3. Run database migrations (for message logging functionality):

```bash
php think migrate:run
```

### Laravel

1. Publish the configuration file:

```bash
php artisan vendor:publish --provider="Xibeicity\Message\Laravel\MessageServiceProvider"
```

2. Configure the related parameters in your .env file (same as ThinkPHP configuration)

3. Run database migrations:

```bash
php artisan migrate
```

## Basic Usage

### Sending SMS

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// Send SMS
$message->driver('sms')->send(['13800138000'], 'Your verification code is: 123456');

// Send SMS using template
$message->driver('sms')->send(['13800138000'], '', [
    'template_code' => 'SMS_123456789',
    'template_param' => [
        'code' => '123456'
    ]
]);
```

### Sending DingTalk Work Notifications

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// Send DingTalk message
$message->driver('dingtalk')->send(['user123'], 'This is a test message');

// Send message with link
$message->driver('dingtalk')->send(['user123'], 'Please check details', [
    'link' => 'https://example.com',
    'title' => 'Notification Title'
]);
```

### Sending DingTalk Robot Messages

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// Send text message
$message->driver('dingtalk_robot')->send(['@all'], 'This is a test message');

// Send markdown message
$message->driver('dingtalk_robot')->send(['@all'], '# Title\nContent', [
    'msg_type' => 'markdown'
]);
```

### Sending WeChat Official Account Template Messages

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// Send template message
$message->driver('wechat_official_account')->send(['OPENID'], '', [
    'template_id' => 'template-id',
    'url' => 'https://example.com',
    'data' => [
        'first' => ['value' => 'You have a new notification'],
        'keyword1' => ['value' => 'Notification content'],
        'remark' => ['value' => 'Please check promptly']
    ]
]);
```

### Sending WeChat Mini Program Subscription Messages

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// Send subscription message
$message->driver('wechat_mini_program')->send(['OPENID'], '', [
    'template_id' => 'template-id',
    'page' => 'pages/index/index',
    'data' => [
        'thing1' => ['value' => 'Product Name'],
        'amount2' => ['value' => '89.90'],
        'date3' => ['value' => '2024-01-01 12:00:00']
    ]
]);
```

### Sending WeChat Robot Messages

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();

// Send text message
$message->driver('wechat_robot')->send(['@all'], 'This is a test message');

// Send markdown message
$message->driver('wechat_robot')->send(['@all'], '# Title\nContent', [
    'msg_type' => 'markdown'
]);
```

## Message List Feature

### Web Interface

Access the message list page:

- ThinkPHP: `http://your-domain/message/list`
- Laravel: `http://your-domain/message/list`

Supports filtering messages by:
- Message driver (SMS/DingTalk/DingTalk Robot/WeChat Official Account/WeChat Mini Program/WeChat Robot)
- Send status (Pending/Success/Failed)
- Recipient

### API Interface

Get message list:

```http
GET /message/list

Request Parameters:
- driver: Message driver (optional, sms/dingtalk/dingtalk_robot/wechat_official_account/wechat_mini_program/wechat_robot)
- status: Send status (optional, pending/success/failed)
- to: Recipient (optional)
- per_page: Items per page (optional, default 15)
- page: Page number (optional, default 1)

Response Example:
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
                "content": "Template message content",
                "status": "success",
                "created_at": "2024-01-01 12:00:00"
            }
        ],
        "total": 1,
        "per_page": 15
    }
}
```

## Advanced Features

### Custom Message Driver

1. Create a custom driver class implementing MessageInterface:

```php
use xibeicity\Message\Contracts\MessageInterface;

class CustomDriver implements MessageInterface
{
    public function send(array $to, string $content, array $options = []): bool
    {
        // Implement sending logic
        return true;
    }

    public function getStatus(): array
    {
        // Return sending status
        return [
            'success' => true,
            'message_id' => 'xxx'
        ];
    }

    public function getError(): ?string
    {
        // Return error message
        return null;
    }
}
```

2. Register the custom driver:

```php
use Xibeicity\Message\MessageManager;

$message = new MessageManager();
$message->extend('custom', function () {
    return new CustomDriver();
});

// Send message using custom driver
$message->driver('custom')->send(['user123'], 'This is a test message');
```