<?php

namespace Xibeicity\Message\Laravel;

use Illuminate\Support\ServiceProvider;
use Xibeicity\Message\MessageManager;

class MessageServiceProvider extends ServiceProvider
{
    /**
     * 注册服务
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/message.php', 'message');

        $this->app->singleton('message', function ($app) {
            return new MessageManager($app['config']['message']);
        });
    }

    /**
     * 引导服务
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/message.php' => config_path('message.php'),
        ], 'message-config');

        // 加载视图
        $this->loadViewsFrom(__DIR__ . '/../views', 'message');

        // 注册路由
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}