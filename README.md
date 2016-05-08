# lumen-api-demo

一个用lumen5.2 和dingoapi 写api的例子

lumen5.1看[这里](https://github.com/liyu001989/lumen-api-demo/tree/5.1) (基本一样的)

## lumen 5.1 升级 5.2

- 先修改compose.json 中的依赖

        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2" // 这是个坑啊

        将5.2的composer.json拿过来对比一下吧

- 修改bootstrap/app.php，照着改
- Illuminate\Contracts\Foundation\Application 改为了Laravel\Lumen\Application，所以修改一下app\providers\EventServiceProvider.php
- 把Middleware cp过来


## 相关文档
- 使用 [dingo/api](https://github.com/dingo/api)
- 用户验证使用 [jwt(json-web-token)](https://github.com/tymondesigns/jwt-auth)
- orm transformer [fractal](http://fractal.thephpleague.com/)
- 文档生成工具 [apidocjs](http://apidocjs.com/)
- api规范参考 [jsonapi.org](http://jsonapi.org/format/)
- rest测试工具 [postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en)


## usage
- composer install
- 复制.env.example 为.env
- 配置各种key和数据库信息

	    JWT_SECRET
            可以用过 php artisan jwt:secret 生成
	    APP_KEY
            lumen 取消了key:generate 所以随便找个地方生成一下吧
            md5(uniqid())，str_random(32) 之类的，或者用jwt:secret生成两个copy一下

- php artisan migrate
- api文档在public/apidoc里面，也可以看[这里](http://lumen-new.lyyw.info/apidoc/)

## 问题总结
### jwt 用法

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
- cursor 解决分页的问题
- 单元测试

## 坑
- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`
- 如果.env的某个值中有空格会报错log not found
