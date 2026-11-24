# MEMORY.md — blogs 项目记忆

> 只记「代码里查不到、下次会再踩」的东西。代码能查到的不复制。
> 凭据只记位置不记值。

## 环境事实（2026-09-24 实测）

- **PHP 未安装**：`php` 不在 PATH；`C:\php`、`C:\xampp`、`C:\laragon`、`C:\wamp64`、`D:\php` 均不存在；注册表无 PHP 安装记录。→ 跑项目前必须先装 PHP。
- **MySQL 8.0 已装且服务在跑**：服务名 `MySQL80`；安装目录 `C:\Program Files\MySQL\MySQL Server 8.0`；客户端 `bin\mysql.exe` 可用。
- **MySQL root 有密码**：`mysql -uroot` 空密码报 `ERROR 1045`。而 `.env` 里 `DB_PASSWORD` 是**空的** → 两边不一致，直接跑必然连不上库。改哪边由用户定。
- **Node v22.16.0 可用**，但 `npm.ps1` 被 PowerShell 执行策略拦截（`running scripts is disabled`）；要用得走 `npm.cmd`。当前项目不需要 npm。
- **PowerShell 执行策略**：本机禁止运行脚本，`powershell-profile-snapshot.ps1` 每次报错。不影响命令本身执行，但别在脚本里依赖 profile。

## 架构决策

- 前端不上构建链：视图用 `URL::asset('css/blog.css')` 直引 `public/css`，刻意绕开 Vite。所以 `node_modules` 缺失、`public/build` 不存在都**不影响运行**。若以后要引 npm 包，需改用 `@vite` 指令并跑 `npm run dev`。

## 踩坑记录

- **`blogs` 表没有 migration**：`database/migrations/` 只有 Laravel 默认 4 张表（users / password_reset_tokens / failed_jobs / personal_access_tokens）。`Blog` 模型操作的 `blogs` 表必须手工建，`php artisan migrate` 建不出来。
- **`users` 表缺 `account` 列**：默认 migration 只有 `name`/`email`/`password`，但 `UserController` 按 `account` 查询 → 需要手工 `ALTER TABLE users ADD account ...`。
- **根路由不存在**：`routes/web.php` 没定义 `/`，跑起来访问 `http://127.0.0.1:8000/` 是 404，要访问 `/index`。
- **模型命名空间特殊**：`Blog`/`User` 在 `App\Models\Home` 下，不在 `App\Models` 根下。新建模型要跟着放。
- **无 `server.php`**：项目根目录没有 `server.php`（Laravel 10 骨架已移除）。IDEA 里配 "PHP Built-in Web Server" 时，Router script 要指向 `vendor\laravel\framework\src\Illuminate\Foundation\resources\server.php`；或者干脆用 Shell Script 跑 `php artisan serve`。

## 外部资源位置

- 项目根：`g:\实训内容、课\blogs`
- PHP 官方 Windows 下载（未装，待用）：https://windows.php.net/download
- Composer Windows 安装器（未装，待用）：https://getcomposer.org/download
- `.env` 实际生效文件：`g:\实训内容、课\blogs\.env`（`APP_KEY` 已生成，`DB_DATABASE=blogdb`）
