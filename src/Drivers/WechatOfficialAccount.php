<?php

namespace Zhangxiaokang\Message\Drivers;

use Zhangxiaokang\Message\Contracts\MessageInterface;
use EasyWeChat\OfficialAccount\Application;

class WechatOfficialAccount implements MessageInterface
{
    protected $app;

    public function __construct(array $config)
    {
        $this->app = new Application([
            'app_id' => $config['app_id'],
            'secret' => $config['app_secret'],
            'token' => $config['token'],
            'aes_key' => $config['aes_key'],
        ]);
    }

    public function send(array $to, string $content, array $options = []): bool
    {
        try {
            $message = [
                'touser' => $to[0],
                'template_id' => $options['template_id'] ?? '',
                'url' => $options['url'] ?? '',
                'data' => array_merge(
                    ['first' => ['value' => $content]],
                    $options['data'] ?? []
                )
            ];

            $response = $this->app->template_message->send($message);
            return $response['errcode'] === 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getStatus(): array
    {
        return [
            'success' => true,
            'message' => '消息已发送'
        ];
    }
}