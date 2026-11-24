<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Home\Blog;

class BlogController extends Controller
{
    /**
     * 首页 / 搜索
     * 未登录：看全部博客；已登录：只看自己的博客
     */
    public function search(Request $request)
    {
        $keyword = trim((string) $request->input('keyword'));
        $userId = $request->session()->get('userId');

        $query = Blog::orderBy('create_time', 'desc')->orderBy('id', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($keyword !== '') {
            $query->where('title', 'like', '%' . $keyword . '%');
        }

        return view('index', [
            'blogs'   => $query->get(),
            'keyword' => $keyword,
        ]);
    }

    /**
     * 新增博客
     */
    public function add(Request $request)
    {
        $userId = $request->session()->get('userId');

        if (! $userId) {
            return redirect('/login')->with('error', '请先登录后再发布博客');
        }

        $title   = trim((string) $request->input('title'));
        $content = trim((string) $request->input('content'));

        if ($title === '' || $content === '') {
            return redirect('/index')->withInput()->with('error', '标题和内容都不能为空');
        }

        if (mb_strlen($title) > 128) {
            return redirect('/index')->withInput()->with('error', '标题不能超过 128 个字');
        }

        $blog          = new Blog();
        $blog->title   = $title;
        $blog->content = $content;
        $blog->user_id = $userId;
        $blog->save();

        return redirect('/index')->with('success', '发布成功');
    }

    /**
     * 删除博客（只允许删除自己的）
     */
    public function del(Request $request, $bid)
    {
        $userId = $request->session()->get('userId');

        if (! $userId) {
            return redirect('/login')->with('error', '请先登录');
        }

        $deleted = Blog::where('id', $bid)->where('user_id', $userId)->delete();

        if (! $deleted) {
            return redirect('/index')->with('error', '博客不存在，或者你没有权限删除它');
        }

        return redirect('/index')->with('success', '已删除');
    }

    /**
     * 修改博客（保存）
     */
    public function mod(Request $request)
    {
        $userId = $request->session()->get('userId');

        if (! $userId) {
            return redirect('/login')->with('error', '请先登录');
        }

        $bid     = $request->input('bid');
        $title   = trim((string) $request->input('title'));
        $content = trim((string) $request->input('content'));

        // 先确认这篇博客存在，并且属于当前登录用户
        $blog = Blog::where('id', $bid)->where('user_id', $userId)->first();

        if (! $blog) {
            return redirect('/index')->with('error', '博客不存在，或者你没有权限修改它');
        }

        if ($title === '' || $content === '') {
            return redirect('/index')->with('error', '标题和内容都不能为空');
        }

        if (mb_strlen($title) > 128) {
            return redirect('/index')->with('error', '标题不能超过 128 个字');
        }

        $blog->title   = $title;
        $blog->content = $content;
        $blog->save();

        return redirect('/index')->with('success', '修改已保存');
    }

    /**
     * 打开单篇博客的修改页
     */
    public function get(Request $request, $bid)
    {
        $userId = $request->session()->get('userId');

        if (! $userId) {
            return redirect('/login')->with('error', '请先登录');
        }

        $blog = Blog::where('id', $bid)->where('user_id', $userId)->first();

        if (! $blog) {
            return redirect('/index')->with('error', '博客不存在，或者你没有权限修改它');
        }

        // 一并带出列表，避免进入修改页后侧栏和列表消失
        $blogs = Blog::orderBy('create_time', 'desc')->orderBy('id', 'desc')
            ->where('user_id', $userId)
            ->get();

        return view('index', [
            'blogs' => $blogs,
            'blog'  => $blog,
        ]);
    }
}
