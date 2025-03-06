<?php

namespace Xibeicity\Message\Drivers;

use Xibeicity\Message\Contracts\MessageInterface;
use EasyWeChat\MiniApp\Application;

class WechatMiniProgram implements MessageInterface
{
    protected $app;
    protected $config;
    protected $error;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->app = new Application([
            'app_id' => $config['app_id'],
            'secret' => $config['app_secret'],
        ]);
    }

    public function send(array $to, string $content, array $options = []): bool
    {
        try {
            // 如果指定了模板名称，从配置中获取模板信息
            if (isset($options['template_name']) && isset($this->config['templates'][$options['template_name']])) {
                $template = $this->config['templates'][$options['template_name']];
                $options = array_merge($template, $options);
            }

            $message = [
                'touser' => $to[0],
                'template_id' => $options['template_id'] ?? '',
                'page' => $options['page'] ?? '',
                'data' => array_merge(
                    ['thing1' => ['value' => $content]],
                    $options['data'] ?? []
                )
            ];

            $response = $this->app->subscribe_message->send($message);
            return $response['errcode'] === 0;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getStatus(): array
    {
        return [
            'success' => true,
            'message' => 'Message sent successfully'
        ];
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}