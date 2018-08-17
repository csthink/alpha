# alpha 部署说明

## 实验环境
* nginx + php7 + mysql + redis + nodeJs

## 安装步骤
```bash
* 克隆文件
git clone https://github.com/csthink/alpha.git 

* 修改目录权限
sudo find storage -type d -exec chmod 777 {} \;
sudo chmod 777 bootstrap/cache;

* 初始化环境
创建 .env 文件 可以通过 .env.example 模板来创建

* 安装php所需扩展包
可选: composer config -g repo.packagist composer https://packagist.laravel-china.org
composer install

php artisan key:generate

* 安装前端所需扩展包
可选: yarn config set registry 'https://registry.npm.taobao.org'
yarn install --no-bin-links
yarn add cross-env

* 编译静态资源css,js
npm run dev 或 npm run watch-poll
```
