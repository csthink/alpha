# alpha 部署说明

## 实验环境
* nginx + php7 + mysql + redis + nodeJs

## 安装步骤
```bash
* 新建 .env 文件

* 安装php所需扩展包
可选: composer config -g repo.packagist composer https://packagist.laravel-china.org
composer install

* 安装前端所需扩展包
可选: yarn config set registry 'https://registry.npm.taobao.org'
yarn install --no-bin-links
yarn add cross-env

* 编译静态资源css,js
npm run dev 或 npm run watch-poll
```
