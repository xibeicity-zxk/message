<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>消息列表</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>消息列表</h2>
        
        <!-- 筛选表单 -->
        <form method="GET" class="mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <select name="driver" class="form-select">
                        <option value="">全部驱动</option>
                        <option value="sms" {{ request('driver') == 'sms' ? 'selected' : '' }}>短信</option>
                        <option value="dingtalk" {{ request('driver') == 'dingtalk' ? 'selected' : '' }}>钉钉</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">全部状态</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>待发送</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>成功</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>失败</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="to" class="form-control" placeholder="接收者" value="{{ request('to') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">筛选</button>
                </div>
            </div>
        </form>

        <!-- 消息列表 -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>驱动</th>
                        <th>接收者</th>
                        <th>内容</th>
                        <th>状态</th>
                        <th>发送时间</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                    <tr>
                        <td>{{ $message->id }}</td>
                        <td>{{ $message->driver }}</td>
                        <td>{{ $message->to }}</td>
                        <td>{{ $message->content }}</td>
                        <td>
                            @if($message->status == 'success')
                                <span class="badge bg-success">成功</span>
                            @elseif($message->status == 'failed')
                                <span class="badge bg-danger" title="{{ $message->error }}">失败</span>
                            @else
                                <span class="badge bg-warning text-dark">待发送</span>
                            @endif
                        </td>
                        <td>{{ $message->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 分页 -->
        <div class="d-flex justify-content-center">
            {{ $messages->links() }}
        </div>
    </div>
</body>
</html>