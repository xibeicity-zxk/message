<?php

namespace Zhangxiaokang\Message\Contracts;

interface MessageInterface
{
    /**
     * 发送消息
     *
     * @param array $to 接收者
     * @param string $content 消息内容
     * @param array $options 额外选项
     * @return bool
     */
    public function send(array $to, string $content, array $options = []): bool;

    /**
     * 获取发送状态
     *
     * @return array
     */
    public function getStatus(): array;

    /**
     * 获取错误信息
     *
     * @return string|null
     */
    public function getError(): ?string;
}