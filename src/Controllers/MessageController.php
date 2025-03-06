<?php

namespace Zhangxiaokang\Message\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Zhangxiaokang\Message\Database\MessageLogger;

class MessageController
{
    /**
     * 获取消息列表
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $query = DB::table('message_logs');

        // 筛选条件
        if ($request->has('driver')) {
            $query->where('driver', $request->input('driver'));
        }
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->has('to')) {
            $query->where('to', $request->input('to'));
        }

        // 排序
        $query->orderBy('created_at', 'desc');

        // 分页
        $perPage = $request->input('per_page', 15);
        $messages = $query->paginate($perPage);

        // 根据请求类型返回不同格式的数据
        if ($request->expectsJson()) {
            return response()->json([
                'code' => 0,
                'message' => 'success',
                'data' => $messages
            ]);
        }

        // 传统模式返回视图
        return view('message::list', compact('messages'));
    }
}