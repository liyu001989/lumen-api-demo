# lumen-api-demo

这是一个比较完整用 lumen 5.2 写的的 REST API 例子，如果你正在做相同的事，那么这个例子或许能帮助你。这个例子使用了 `dingo/api` 写 rest 风格的 api，jwt 实现登录，功能上很简单，登录，注册，发帖，评论，还写了单元测试。

lumen5.1看[这里](https://github.com/liyu001989/lumen-api-demo/tree/5.1) (不更新了)

[ENGLISH README](./EN_README.md)


## USEFUL LINK
- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- json-web-token(jwt) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc [apidocjs](http://apidocjs.com/)
- rest api [jsonapi.org](http://jsonapi.org/format/)
- debug rest api [postman](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en)
- 参考文章 [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- 在线api文档 [http://lumen-new.lyyw.info/apidoc](https://lumen-new.lyyw.info/apidoc)


## USAGE
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


## REST API DESIGN

大概举个例子说明一下 rest api 吧，看了很多人设计的 api，感觉都不太好。并不是定义一个路由，返回个值就叫 rest api。

        例子： 用户，帖子，评论
        get    /api/posts              	 帖子列表
        post   /api/posts              	 创建帖子
        get    /api/posts/5            	 id为5的帖子详情
        put    /api/posts/5            	 替换帖子5的全部信息
        patch  /api/posts/5            	 修改部分帖子5的信息
        delete /api/posts/5            	 删除帖子5
        get    /api/posts/5/comments     帖子5的评论列表
        post   /api/posts/5/comments     添加评论
        get    /api/posts/5/comments/8   id为5的帖子的id为8的评论详情
        put    /api/posts/5/comments/8   替换帖子评论内容
        patch  /api/posts/5/comments/8   部分更新帖子评论
        delete /api/posts/5/comments/8   删除某个评论
        get    /api/users/4/posts        id为4的用户的帖子列表
        get    /api/user/posts           当前用户的帖子列表

## 问题总结

### lumen 5.1 upgrade to  5.2

- fix compose.json

        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2" // 这是个坑啊
      
        将5.2的composer.json拿过来对比一下吧

- fix bootstrap/app.php
- Illuminate\Contracts\Foundation\Application 改为了Laravel\Lumen\Application，所以修改一下app\providers\EventServiceProvider.php
- 可以从 5.2 的项目中，把 Middleware cp 过来


### jwt 使用

lumen 5.2 取消了session，没有了 auth 的实例，所以使用jwt的时候需要配置一下，注意 config/auth.php 中的配置，而且 user 的 model 需要实现 `Tymon\JWTAuth\Contracts\JWTSubject`;

### mail 使用

- composer 加 illuminate/mail 和 guzzlehttp/guzzle 这两个库
- 在 bootstrap/app.php 或者 provider 中注册 mail 服务
- 增加配置 mail 和 services, 从 laravel 项目里面 cp 过来
- 在 env 中增加 `MAIL_DRIVER`，账户，密码等配置

### cors

dingoapi 返回的时候回触发事件 ResponseWasMorphed, 所以可以响应这个事件，增加 header。

我是写了一个全局的 middleware。

### transformer 使用

dingo/api 使用了 [Fractal](http://fractal.thephpleague.com/) 做数据转换，fractal 提供了3种基础的序列化格式，Array，DataArray，JsonApi，在这里有详细的说明 [http://fractal.thephpleague.com/serializers/](http://fractal.thephpleague.com/serializers/)。DataArray 是默认的，也就是所有资源一定有data和meta。当然也可以按下面这样自定义：

        只需要在 bootstrap/app.php 中设置 serializer 就行了。具体见 bootstrap/app.php 有注释
        $app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new League\Fractal\Manager;
            // 自定义的和fractal提供的
            // $serializer = new League\Fractal\Serializer\JsonApiSerializer();
            $serializer = new League\Fractal\Serializer\ArraySerializer();
            // $serializer = new ApiDemo\Serializers\NoDataArraySerializer();
            $fractal->setSerializer($serializer);,
            return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });

个人认为默认的 DataArray 就很好用了，基本满足了 API 的需求

### repository

我随便写的，`rinvex/repository` 和 `prettus/l5-repository` 这两个库都不错，大家可以试试

## TODO
- [ ] lumen 下邮件发送，注册验证
- [ ] cursor 解决无限下拉的问题
- [x] 单元测试

## 坑
- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`
- 如果 .env 的某个值中有空格会报错 log not found，laravel 没有这个问题

## License

[MIT license](http://opensource.org/licenses/MIT)
