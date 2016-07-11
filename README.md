# lumen-api-demo

应该是一个比较完整的lumen api例子了，思路跟laravel完全一样，使用laravel开发起来会更爽。这个例子使用了`dingo/api`写rest风格的api，jwt实现登录，功能上很简单，登录，注册，发帖，评论，写了单元测试，还没写...

lumen5.1看[这里](https://github.com/liyu001989/lumen-api-demo/tree/5.1) (不更新了)


## 相关文档
- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- json-web-token(jwt) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- doc生成工具 [apidocjs](http://apidocjs.com/)
- api规范 [jsonapi.org](http://jsonapi.org/format/)
- rest测试工具 [postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en)
- 参考文章 [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- 在线api文档 [http://lumen-new.lyyw.info/apidoc](http://lumen-new.lyyw.info/apidoc)


## usage
```
$ git clone git@github.com:liyu001989/lumen-api-demo.git
$ composer install
$ cp .env.example .env
$ vim .env
        DB_*
            填写数据库相关配置 your database configuration
	    JWT_SECRET
            php artisan jwt:secret
	    APP_KEY
            lumen 取消了key:generate 所以随便找个地方生成一下吧
            md5(uniqid())，str_random(32) 之类的，或者用jwt:secret生成两个copy一下

$ php artisan migrate
$ 生成文档我是这么写的 apidoc -i App/Http/Controller/Api/v1 -o public/apidoc
$ api文档在public/apidoc里面, 也可以看上面的 `在线api文档`
```

## 问题总结

### lumen 5.1 upgrade to  5.2

- fix compose.json

        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2" // 这是个坑啊

        将5.2的composer.json拿过来对比一下吧

- fix bootstrap/app.php
- Illuminate\Contracts\Foundation\Application 改为了Laravel\Lumen\Application，所以修改一下app\providers\EventServiceProvider.php
- 把Middleware cp过来


### jwt 使用

lumen 5.2取消了session，没有了auth的实例，所以使用jwt的时候需要配置一下，注意config/auth.php中的配置，而且user的model需要实现Tymon\JWTAuth\Contracts\JWTSubject;

### mail 使用

- composer 加illuminate/mail 和guzzlehttp/guzzle 这两个库
- 在bootstrap/app.php 或者 provider中注册mail服务
- 增加配置 mail和services, 从laravel项目里面cp过来
- 在env中增加`MAIL_DRIVER`，账户，密码等配置

### cors

dingoapi 返回的时候回触发事件ResponseWasMorphed, 所以可以响应这个事件，增加header。

但是这样就不能控制哪些路由需要这些header，所以还是写在middleware里面比较好

## TODO
- lumen 下邮件发送，注册验证
- cursor 解决无限下拉的问题
- 单元测试
- 尝试增加mongodb的repository

## 坑
- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`
- 如果.env的某个值中有空格会报错log not found

## License

[MIT license](http://opensource.org/licenses/MIT)
