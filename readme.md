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

* git 初始化
git init 

* 将项目所有文件纳入到 Git 中
git add -A

* 安装第三方库
composer require --dev barryvdh/laravel-ide-helper
composer require --dev barryvdh/laravel-debugbar

```
