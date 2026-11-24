<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * 用户登录
     * 说明：本项目是教学项目，users.password 存的是明文，直接按明文比对。
     * 正式上线应改为 Hash::check($password, $user->password)。
     */
    public function login(Request $request)
    {
        $account  = trim((string) $request->input('account'));
        $password = (string) $request->input('password');

        if ($account === '' || $password === '') {
            return view('login', ['message' => '请输入账号和密码']);
        }

        $user = DB::table('users')
            ->where(['account' => $account, 'password' => $password])
            ->first();

        if (! $user) {
            return view('login', ['message' => '账号或密码不一致']);
        }

        // 登录成功后清掉旧 session，避免会话固定
        $request->session()->regenerate();
        $request->session()->put('account', $user->account);
        $request->session()->put('userId', $user->id);

        return redirect('/index')->with('success', '欢迎回来，' . $user->account);
    }

    /**
     * 退出登录
     */
    public function loginoff(Request $request)
    {
        $request->session()->forget('account');
        $request->session()->forget('userId');
        $request->session()->regenerate();

        return redirect('/index')->with('success', '已退出登录');
    }
}
