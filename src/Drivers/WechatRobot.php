<?php

namespace Xibeicity\Message\Drivers;

use Xibeicity\Message\Contracts\MessageInterface;
use GuzzleHttp\Client;

class WechatRobot implements MessageInterface
{
    protected $webhook;
    protected $client;
    protected $error;

    public function __construct(array $config)
    {
        $this->webhook = $config['webhook'];
        $this->client = new Client();
    }

    public function send(array $to, string $content, array $options = []): bool
    {
        try {
            $message = [
                'msgtype' => 'text',
                'text' => ['content' => $content]
            ];

            if (!empty($options['mentioned_list'])) {
                $message['text']['mentioned_list'] = $options['mentioned_list'];
            }

            if (!empty($options['mentioned_mobile_list'])) {
                $message['text']['mentioned_mobile_list'] = $options['mentioned_mobile_list'];
            }

            $response = $this->client->post($this->webhook, [
                'json' => $message,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return $result['errcode'] === 0;
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