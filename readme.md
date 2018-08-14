# laravel_alpha

```bash
* 本地新建项目
composer create-project laravel/laravel Laravel --prefer-dist "5.5.*"

* 修改 .gitignore 文件

* 安装前端所需扩展包
yarn config set registry 'https://registry.npm.taobao.org'
yarn install --no-bin-links
yarn add cross-env

* 编译静态资源css,js
npm run dev 或 npm run watch-poll

* 安装第三方库
composer require --dev barryvdh/laravel-ide-helper
composer require --dev barryvdh/laravel-debugbar

composer require "overtrue/laravel-lang:~3.0" 
需要修改 config/app.php 中 'locale' => 'zh-CN'

```

## artisan
 
```bash
* 创建控制器
php artisan make:controller UserController

* 创建模型文件
php artisan make:model Models/Users

* 执行数据迁移
php artisan migrate

* 回滚数据迁移
php artisan migrate:rollback

* 数据库重置操作
php artisan migrate:refresh
```


## Eloquent 表命名约定
* Article 数据模型类对应 articles 表；
* User 数据模型类对应 users 表；
* BlogPost 数据模型类对应 blog_posts 表


## tinker 环境
Tinker 是一个 REPL (read-eval-print-loop)，REPL 指的是一个简单的、可交互式的编程环境，通过执行用户输入的命令，并将执行结果直接打印到命令行界面上来完成整个操作

```bash
php artisan tinker
```

```php 
use App\Models\User;
User::create(['name' => 'jack', 'email' => 'csthink@qq.com', 'password' => bcrypt('111111')]);

```

## 路由
* 隐性路由模型绑定

```php
// 路由声明时必须使用 Eloquent 模型的单数小写格式来作为路由片段参数，User 对应 {user}
Route::get('Users/{user}', 'UsersController@show')->name('users.show')

// 控制器方法传参中必须包含对应的 Eloquent 模型类型声明，并且是有序的
public function show(Users $user) 
{
    return view('users.show', compact('user'));
}

// 当请求 http://xx.com/users/1 并且满足以上两个条件时，Laravel 将会自动查找 ID 为 1 的用户并赋值到变量 $user 中，
// 如果数据库中找不到对应的模型实例，会自动生成 HTTP 404 响应。
// 将用户对象 $user 通过 compact 方法转化为一个关联数组，并作为第二个参数传递给 view 方法，将数据与视图进行绑定
```
