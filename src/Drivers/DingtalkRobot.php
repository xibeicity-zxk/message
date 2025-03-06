<?php

namespace Xibeicity\Message\Drivers;

use Zhangxiaokang\Message\Contracts\MessageInterface;
use GuzzleHttp\Client;

class DingtalkRobot implements MessageInterface
{
    protected $webhook;
    protected $secret;
    protected $client;

    public function __construct(array $config)
    {
        $this->webhook = $config['webhook'];
        $this->secret = $config['secret'] ?? '';
        $this->client = new Client();
    }

    public function send(array $to, string $content, array $options = []): bool
    {
        try {
            $timestamp = time() * 1000;
            $sign = '';
            
            if ($this->secret) {
                $stringToSign = $timestamp . "\n" . $this->secret;
                $sign = base64_encode(hash_hmac('sha256', $stringToSign, $this->secret, true));
            }
            
            $url = $this->webhook;
            if ($sign) {
                $url .= "&timestamp={$timestamp}&sign={$sign}";
            }

            $message = [
                'msgtype' => 'text',
                'text' => ['content' => $content]
            ];

            if (!empty($options['at'])) {
                $message['at'] = [
                    'atMobiles' => $options['at'],
                    'isAtAll' => $options['at_all'] ?? false
                ];
            }

            $response = $this->client->post($url, [
                'json' => $message,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            return $result['errcode'] === 0;
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