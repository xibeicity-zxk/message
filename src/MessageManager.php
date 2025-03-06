<?php

namespace Zhangxiaokang\Message;

use Zhangxiaokang\Message\Contracts\MessageInterface;

class MessageManager
{
    /**
     * 消息驱动实例
     *
     * @var array
     */
    protected $drivers = [];

    /**
     * 默认驱动
     *
     * @var string
     */
    protected $default = 'sms';

    /**
     * 配置信息
     *
     * @var array
     */
    protected $config = [];

    /**
     * 构造函数
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 获取消息驱动实例
     *
     * @param string|null $name
     * @return MessageInterface
     * @throws \InvalidArgumentException
     */
    public function driver(?string $name = null): MessageInterface
    {
        $name = $name ?: $this->default;

        if (!isset($this->drivers[$name])) {
            $this->drivers[$name] = $this->createDriver($name);
        }

        return $this->drivers[$name];
    }

    /**
     * 创建消息驱动实例
     *
     * @param string $name
     * @return MessageInterface
     * @throws \InvalidArgumentException
     */
    protected function createDriver(string $name): MessageInterface
    {
        if (!isset($this->config[$name])) {
            throw new \InvalidArgumentException("Message driver [{$name}] not configured.");
        }

        $config = $this->config[$name];
        $method = 'create' . ucfirst($name) . 'Driver';

        if (method_exists($this, $method)) {
            return $this->$method($config);
        }

        throw new \InvalidArgumentException("Driver [{$name}] not supported.");
    }

    /**
     * 动态调用驱动方法
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->driver()->$method(...$parameters);
    }
}