<?php

namespace Xibeicity\Message\Database;

use Illuminate\Support\Facades\DB;
use Think\Db\Facade\Db as ThinkDb;

class MessageLogger
{
    protected $config;
    protected $isLaravel;

    public function __construct($config)
    {
        $this->config = $config;
        $this->isLaravel = class_exists('Illuminate\Support\Facades\DB');
    }

    public function log($driver, $to, $content, $data = [], $status = 'pending', $error = null)
    {
        if (!$this->config['enabled']) {
            return;
        }

        $data = [
            'driver' => $driver,
            'to' => $to,
            'content' => $content,
            'data' => json_encode($data),
            'status' => $status,
            'error' => $error,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->isLaravel) {
            $connection = $this->config['connection'] ?? null;
            DB::connection($connection)->table($this->config['table'])->insert($data);
        } else {
            $connection = $this->config['connection'] ?? '';
            ThinkDb::connect($connection)->table($this->config['table'])->insert($data);
        }
    }

    public function updateStatus($id, $status, $error = null)
    {
        if (!$this->config['enabled']) {
            return;
        }

        $data = [
            'status' => $status,
            'error' => $error,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->isLaravel) {
            $connection = $this->config['connection'] ?? null;
            DB::connection($connection)->table($this->config['table'])->where('id', $id)->update($data);
        } else {
            $connection = $this->config['connection'] ?? '';
            ThinkDb::connect($connection)->table($this->config['table'])->where('id', $id)->update($data);
        }
    }
}