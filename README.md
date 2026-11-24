# 个人博客（Laravel 10）

基于 Laravel 10 + MySQL 的服务端渲染个人博客，课程实训项目。实现用户登录与文章的全生命周期管理，
界面为橙白暖调卡片式设计，样式全部手写 CSS，不引入任何前端框架。

## 功能

- **用户**：登录 / 登出，session 鉴权，登录后仅展示与操作自己的文章
- **文章**：列表、发布、修改、删除（修改页保留侧栏与文章列表）
- **搜索**：按标题 + 正文关键词过滤，空关键词自动忽略
- **体验**：操作成功 / 失败顶部提示条，校验失败回填原输入，空列表有占位状态

## 安全与健壮性

- 修改 / 删除均按 `id + user_id` 校验归属，越权访问返回提示而非报错
- 删除改为 `POST + CSRF`，避免 GET 链接被预取误删
- 查询结果判空，不存在的 id 不会触发 500
- 标题限 128 字，输入服务端校验

## 技术栈

PHP 8.1+ · Laravel 10 · MySQL 8 · Blade · 原生 CSS（无 npm / Vite 构建）

## 本地运行

```bash
composer install
cp .env.example .env && php artisan key:generate
# 编辑 .env：DB_DATABASE=blogdb、DB_USERNAME、DB_PASSWORD
mysql -uroot -p --default-character-set=utf8mb4 < database/blogdb.sql
php artisan serve
```

访问 <http://127.0.0.1:8000/index>，测试账号 **user / user1**。

> 导入 SQL 需带 `--default-character-set=utf8mb4`，否则中文报 1366。

## 目录结构（主要文件）

```
app/Http/Controllers/BlogController.php   文章的增删改查与搜索
app/Http/Controllers/UserController.php   登录、登出
app/Models/Home/Blog.php                  文章模型
app/Models/Home/User.php                  用户模型
routes/web.php                            路由定义
resources/views/index.blade.php           首页（列表 / 发布 / 修改共用）
resources/views/login.blade.php           登录页
public/css/blog.css                       全部样式
database/blogdb.sql                       建库建表与示例数据
```

## 已知限制

- 密码为明文比对（教学写法），生产环境需改为 `Hash::check`
- 暂无分页；建表依赖 `database/blogdb.sql`，未使用 migration
- 文章表字符集为 `utf8mb3`，存 emoji 需转 `utf8mb4`
