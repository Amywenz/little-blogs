# AGENTS.md — blogs 项目约定

> 本文件定义本项目的硬约束。改结构时先改本文件，再改实践。
> 待确认字段见文末「待确认」，确认后填入并删除该条。

## 项目概览

| 项 | 值 |
| --- | --- |
| 项目名 | blogs |
| 定位 | Laravel 博客系统（搜索 / 发布 / 修改 / 删除 + 登录） |
| 类型 | 单体 Web 应用（服务端渲染，非前后端分离） |
| 创建日期 | 2026-09-24 |
| 状态 | 开发中，未部署 |

## 技术栈

- **后端**：PHP ^8.1 + Laravel ^10.10
- **数据库**：MySQL 8.0（库名 `blogdb`）
- **模板**：Blade（`resources/views`）
- **前端**：原生 CSS（`public/css/blog.css`、`app.css`），**未使用** Tailwind / Vue / React
- **构建工具**：Vite 已配置但当前未被视图引用（视图用 `URL::asset()` 直接引 CSS），**运行不需要 npm**
- **会话**：file 驱动；**队列**：sync；**缓存**：file
- **IDE**：IntelliJ IDEA Ultimate 2026.1.2（`D:\IntelliJ IDEA 2026.1.2`）

## 目录约定

```
app/Http/Controllers/     BlogController.php（博客 CRUD）、UserController.php（登录/登出）
app/Models/Home/          Blog.php、User.php（注意：不在 app/Models 根下，在 Home 子命名空间）
resources/views/          index.blade.php（主页+列表+表单）、login.blade.php
public/css/               手写样式，不走构建流程
database/migrations/      仅 Laravel 默认 4 张表，业务表靠手工 SQL
```

## 运行方式

标准启动（需先装 PHP，见 MEMORY.md）：

```bash
php artisan serve          # 默认 http://127.0.0.1:8000
```

- 入口页是 **`/index`**，不是 `/`（`routes/web.php` 未定义根路由，访问 `/` 会 404）
- 登录页 `/login`
- 改 `.env` 后需 `php artisan config:clear`
- 改路由/类文件后需 `php artisan optimize:clear`

## 编码约定

- 注释中文，标识符英文
- 控制器方法保持「取参 → 校验 → 操作模型 → 返回 view/redirect」的直白写法，不引入 Service/Repository 层
- 模型放 `App\Models\Home` 命名空间，与现有代码一致
- 新增业务表必须同时提交 migration，禁止再手工建表（现有 `blogs` 表是历史遗留）

## 数据库

- 连接配置位置：`.env`（凭据只记位置不记值）
- 已用到的表：
  - `users` — 需含 `id`、`account`、`password`（`password` 为**明文比对**，非 Hash）
  - `blogs` — 需含 `id`、`user_id`、`title`、`content`、`create_time`
- 模型 `Blog` 已设 `$timestamps = false`，时间字段名是 `create_time` 而非 Laravel 默认的 `created_at`

## 安全注意

- `UserController::login` 用明文 `password` 直接查库，属教学写法；若上线必须改为 `Hash::check`
- `BlogController` 的 `del`/`mod` 未做归属校验，任意登录用户可改他人博客

## 待确认

- [ ] 项目定位：课程实训作业 / 真实业务项目？
- [ ] 是否需要部署上线，目标平台？
- [ ] 是否已有 `blogs` 建表 SQL 或初始数据脚本？在哪个文件？
