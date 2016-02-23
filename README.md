# lumen-api-demo

一个用lumen5.2 和dingoapi 写api的例子

lumen5.1看[这里](https://github.com/liyu001989/lumen-api-demo/tree/5.1) (基本一样的)

## lumen 5.1 升级 5.2

- 先修改compose.json 中的依赖

        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2" // 这是个坑啊

        直接将5.2的composer.json拿来替换了

- 修改bootstrap/app.php，照着改
- Illuminate\Contracts\Foundation\Application 改为了Laravel\Lumen\Application，所以修改一下app\providers\EventServiceProvider.php
- 把Middleware cp过来


## 相关文档
- 使用 [dingo/api](https://github.com/dingo/api)
- 用户验证使用 [jwt(json-web-token)](https://github.com/tymondesigns/jwt-auth)
- orm transformer [fractal](http://fractal.thephpleague.com/)
- 文档使用 [apidocjs](http://apidocjs.com/)
- api规范参考 [jsonapi.org](http://jsonapi.org/format/)
- rest测试工具 [postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en)


## usage
- composer install
- 复制.env.example 为.env
- 配置数据库信息
- php artisan migrate
- v2 版本的api 只是个例子，可以删除


## TODO
- 研究一下5.2 的认证

## 坑
- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`
- 如果.env的某个值中有空格会报错log not found
