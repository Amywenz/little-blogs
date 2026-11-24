<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>登录 · Little Blog</title>
    <link rel="stylesheet" href="{{ URL::asset('css/blog.css') }}?v={{ filemtime(public_path('css/blog.css')) }}">
</head>
<body class="auth-page">

<div class="auth-wrap">
    <div class="auth-card">
        <div class="auth-brand">
            <span class="brand-mark">L</span>
            <span class="brand-text">Little&nbsp;Blog</span>
        </div>

        <h1 class="auth-title">欢迎回来</h1>
        <p class="auth-sub">登录后可以发布、修改和删除自己的博客</p>

        @if(session('error'))
            <div class="alert alert-error">
                <span class="alert-dot"></span>{{ session('error') }}
            </div>
        @endif

        @if(isset($message) && $message)
            <div class="alert alert-error">
                <span class="alert-dot"></span>{{ $message }}
            </div>
        @endif

        <form class="auth-form" action="/user/login" method="post">
            {{ csrf_field() }}

            <label class="auth-label" for="account">账号</label>
            <input class="field" id="account" type="text" name="account"
                   value="{{ old('account') }}" placeholder="请输入账号" autocomplete="username">

            <label class="auth-label" for="password">密码</label>
            <input class="field" id="password" type="password" name="password"
                   placeholder="请输入密码" autocomplete="current-password">

            <button class="btn btn-primary btn-block" type="submit">登录</button>
        </form>

        <div class="auth-foot">
            <a class="link-quiet" href="/index">← 先去逛逛，不登录</a>
        </div>
    </div>
</div>

</body>
</html>
