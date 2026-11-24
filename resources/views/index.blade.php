<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Little Blog · 个人博客</title>
    <link rel="stylesheet" href="{{ URL::asset('css/blog.css') }}?v={{ filemtime(public_path('css/blog.css')) }}">
</head>
<body>

<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="/index">
            <span class="brand-mark">L</span>
            <span class="brand-text">Little&nbsp;Blog</span>
        </a>

        <form class="search" action="/blog/search" method="get" role="search">
            <input class="search-input" type="text" name="keyword"
                   value="{{ $keyword ?? '' }}" placeholder="搜索博客标题…" aria-label="搜索博客标题">
            <button class="search-btn" type="submit">搜索</button>
        </form>

        <div class="account">
            @if(session()->exists("account"))
                <span class="avatar">{{ mb_substr(session()->get("account"), 0, 1) }}</span>
                <span class="account-name">{{ session()->get("account") }}</span>
                <a class="link-quiet" href="/user/loginoff">退出</a>
            @else
                <a class="btn btn-ghost btn-sm" href="/login">登录</a>
            @endif
        </div>
    </div>
</header>

<main class="page">

    @if(session('error'))
        <div class="alert alert-error">
            <span class="alert-dot"></span>{{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            <span class="alert-dot"></span>{{ session('success') }}
        </div>
    @endif

    <div class="layout">

        <div class="col-main">

            <!-- 发布 / 修改表单 -->
            <section class="composer card">
                <div class="card-head">
                    <h2>{{ isset($blog) ? '修改博客' : '写点什么' }}</h2>
                    @if(isset($blog))
                        <a class="link-quiet" href="/index">取消修改</a>
                    @endif
                </div>

                <form action="{{ isset($blog) ? '/blog/mod' : '/blog/add' }}" method="post">
                    @csrf
                    <input type="hidden" name="bid" value="{{ isset($blog) ? $blog->id : '' }}">

                    <input class="field" type="text" name="title" maxlength="128"
                           placeholder="博客标题"
                           value="{{ isset($blog) ? $blog->title : old('title') }}">

                    <textarea class="field field-area" name="content" rows="3"
                              placeholder="写下此刻的想法…">{{ isset($blog) ? $blog->content : old('content') }}</textarea>

                    <div class="composer-foot">
                        <span class="hint">
                            @if(session()->exists("account"))
                                标题 128 字以内 · 内容不限
                            @else
                                登录后才能发布
                            @endif
                        </span>
                        <div class="composer-actions">
                            @if(isset($blog))
                                <a class="btn btn-ghost" href="/index">取消</a>
                            @endif
                            <button class="btn btn-primary" type="submit">
                                {{ isset($blog) ? '保存修改' : '发布' }}
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <!-- 文章列表 -->
            <div class="posts-head">
                <h2>
                    @if(($keyword ?? '') !== '')
                        「{{ $keyword }}」的搜索结果
                    @else
                        全部文章
                    @endif
                </h2>
                <span class="pill">{{ isset($blogs) ? $blogs->count() : 0 }} 篇</span>
            </div>

            <section class="posts">
                @if(isset($blogs) && $blogs->count())
                    @foreach($blogs as $b)
                        <article class="card post" id="post-{{ $b->id }}">
                            <div class="post-head">
                                <h3>{{ $b->title }}</h3>
                                <div class="post-actions">
                                    @if(session()->exists("account"))
                                        <a class="btn btn-sm" href="/blog/mod/{{ $b->id }}">修改</a>
                                        <form action="/blog/del/{{ $b->id }}" method="post"
                                              onsubmit="return confirm('确定删除《{{ $b->title }}》吗？');">
                                            @csrf
                                            <button class="btn btn-sm btn-danger" type="submit">删除</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <p class="post-meta">{{ $b->create_time }}</p>
                            <p class="post-body">{{ $b->content }}</p>
                        </article>
                    @endforeach
                @else
                    <div class="card empty">
                        <p class="empty-title">
                            @if(($keyword ?? '') !== '')
                                没有找到匹配的文章
                            @else
                                这里还空着
                            @endif
                        </p>
                        <p class="muted">
                            @if(($keyword ?? '') !== '')
                                换个关键词试试，或者清空搜索看全部文章
                            @else
                                发布你的第一篇博客吧
                            @endif
                        </p>
                    </div>
                @endif
            </section>
        </div>

        <!-- 侧栏：标题索引 + 小贴士 -->
        <aside class="side-col">
            <div class="card side">
                <div class="card-head">
                    <h2>文章目录</h2>
                    <span class="pill">{{ isset($blogs) ? $blogs->count() : 0 }}</span>
                </div>

                @if(isset($blogs) && $blogs->count())
                    <ul class="toc">
                        @foreach($blogs as $b)
                            <li>
                                <a href="#post-{{ $b->id }}">
                                    <span class="toc-title">{{ $b->title }}</span>
                                    @if($b->create_time)
                                        <span class="toc-time">{{ \Illuminate\Support\Str::substr($b->create_time, 5, 5) }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="muted side-empty">暂无文章</p>
                @endif
            </div>

            <div class="card note">
                <h3 class="note-title">写作小贴士</h3>
                <ul class="note-list">
                    <li>标题写具体一点，搜索时更容易命中</li>
                    <li>正文支持换行，分段会更易读</li>
                    <li>删除后无法恢复，操作前想清楚</li>
                </ul>
            </div>
        </aside>

    </div>
</main>

<footer class="footer">
    Little Blog · Laravel 10 实训项目
</footer>

</body>
</html>
