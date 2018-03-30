# lumen-api-demo

这是一个比较完整用 lumen 5.5 写的的 REST API 例子。使用了 `dingo/api` ，jwt 实现登录，功能上很简单，登录，注册，发帖，评论，单元测试(正在补充)。

[![StyleCI](https://styleci.io/repos/44219096/shield)](https://styleci.io/repos/44219096)
[![License](https://img.shields.io/github/license/liyu001989/lumen-api-demo.svg)](LICENSE)
[![donate](https://img.shields.io/badge/paypal-donate-red.svg)](https://paypal.me/liyu001989)
[![donate](https://img.shields.io/badge/%E7%BA%A2%E5%8C%85-donate-red.svg)](https://cloud.githubusercontent.com/assets/2981799/25706351/cfba493c-3112-11e7-9985-aec05ff9734c.png)

lumen5.x 请看对应的分支

有需要随时联系我 

- lumen/laravel/restful 交流群: 216721539
- email: liyu001989@gmail.com

[ENGLISH README](./EN_README.md)

## 教程

[Laravel 教程 - 实战构架 API 服务器](https://laravel-china.org/courses/laravel-advance-training-5.5)

## USEFUL LINK

读文档很重要，请先仔细读读文档 laravel, dingo/api，jwt，fractal 的文档。

- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- dingo api 中文文档 [dingo-api-wiki-zh](https://github.com/liyu001989/dingo-api-wiki-zh)
- jwt(json-web-token) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc 生成在线文档 [apidocjs](http://apidocjs.com/)
- rest api 参考规范 [jsonapi.org](http://jsonapi.org/format/)
- api 调试工具 [postman](https://www.getpostman.com/)
- 有用的文章 [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- php lint [phplint](https://github.com/overtrue/phplint)
- Laravel 理念 [From Apprentice To Artisan](https://my.oschina.net/zgldh/blog/389246)
- 我对 REST 的理解 [http://blog.lyyw.info/2017/02/09/2017-02-09%20%E5%AF%B9rest%E7%9A%84%E7%90%86%E8%A7%A3/](http://blog.lyyw.info/2017/02/09/2017-02-09%20%E5%AF%B9rest%E7%9A%84%E7%90%86%E8%A7%A3/)
- 项目api在线文档 [http://lumen.lyyw.info/apidoc](https://lumen.lyyw.info/apidoc)

## USAGE

```
$ git clone git@github.com:liyu001989/lumen-api-demo.git
$ composer install
$ 设置 `storage` 目录必须让服务器有写入权限。
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
$ php artisan db:seed (默认添加了10个用户，50篇帖子, 100条评论)

头信息中可以增加 Accept:application/vnd.lumen.v1+json 切换v1和v2版本

api文档在public/apidoc里面有一份，网络不好的可以直接查看本地的文档, 也可以看上面的 `项目api在线文档`

我是这样生成的: apidoc -i App/Http/Controllers/Api/V1/ -o public/apidoc/

```
如果访问一直不对，可以进入public 目录执行 `php -S localhost:8000 -t public`，然后尝试调用几个接口，从而确定是否为web服务器的配置问题。

## REST API DESIGN

大概举个例子说明一下 rest api 吧

github 的 api 真的很有参考价值 [github-rest-api](https://developer.github.com/v3/)

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
    
        // 登录，刷新，登出
        // 或许可以有更好的命名
        post    /api/authorizations  创建一个token
        put     /api/authorizations/current  刷新当前 token
        delete  /api/authorizations/current  删除当前 token

## 问题总结

<details>
  <summary>Lumne 和 Laravel 选哪个</summary>

  首先建议大家使用 Laravel，参考超哥的答案 [https://laravel-china.org/articles/5079/laravel-or-lumen](https://laravel-china.org/articles/5079/laravel-or-lumen)。Laravel 提供了更全的功能，更容易使用，非常方便。Lumen 只是为了Api 而生，而我们通常的业务场景会是一套 Api 和一套后台管理系统，使用 Laravel 会让你更快更好的完成需求。非常非常不建议新手直接使用 Lumen。

</details>

<details>
  <summary>jwt 使用</summary>

lumen 5.2 取消了session，没有了 auth 的实例，所以使用jwt的时候需要配置一下，注意 config/auth.php 中的配置，而且 user 的 model 需要实现 `Tymon\JWTAuth\Contracts\JWTSubject`;

基本用法, jwt 会 encode 对应模型的 id，生成token，客户端拿到 token，放在 Authorization header 中

```
Authorization: Bearer token
```

验证的逻辑就是 decode token 拿到id，然后找到对应的用户。当然了，你可能需要 encode 额外的字段，那么可以使用 CustomClaims。

token 有两个时间，一个是过期时间(ttl)，一个是可刷新时间(refresh_ttl)。怎么理解，比如 ttl 是 1 天，refresh_ttl 是1周，那么 token 一天后过期，但是1周之内你仍然可以用这个 token 换取一个新的 token，而这个新 token 又会在 1 天后过期，1周内可刷新。

举个例子，用户登录了你的应用，并且每天都会打开你的应用，你的应用如果发现这个 token 过期了，会主动刷新一次，如果成功那么用户依然是登录的。当用户1周都没有登录过你的应用，那么他就需要重新登录了。

客户端的逻辑应该是，首先判断这个 token 是否过期了，1是通过两个 ttl 判断，因为客户端是知道这两个时间的，2是调用需要授权的接口返回的状态码（401），判断过期了则主动尝试刷新，刷新成功了，重置token和时间，失败了，则跳转到登录页面。
</details>

<details>
  <summary>使用mail</summary>

  写了个例子，注册之后给用户发送邮件, 可以参考一下。

- composer 加 illuminate/mail 和 guzzlehttp/guzzle 这两个库
- 在 bootstrap/app.php 或者 provider 中注册 mail 服务
- 增加配置 mail 和 services, 从 laravel 项目里面 cp 过来
- 在 env 中增加 `MAIL_DRIVER`，账户，密码等配置
  </details>

<details>
  <summary>transformer 的正确使用</summary>

  transformer 是个数据转换层，帮助你格式化资源。还可以帮助你处理资源之间的引用关系。

  试着体会一下以下几个url的也许就明白了

  - [http://lumen.lyyw.info/api/posts](http://lumen.lyyw.info/api/posts)  所有帖子列表
  - [http://lumen.lyyw.info/api/posts?include=user](http://lumen.lyyw.info/api/posts?include=user) 所有帖子列表及发帖用户
  - [http://lumen.lyyw.info/api/posts?include=user,comments](http://lumen.lyyw.info/api/posts?include=user,comments) 帖子列表及发帖的用户和发帖的评论
  - [http://lumen.lyyw.info/api/posts?include=user,comments:limit(1)](http://lumen.lyyw.info/api/posts?include=user,comments:limit(1)) 帖子列表及发帖的用户和发帖的1条评论
  - [http://lumen.lyyw.info/api/posts?include=user,comments.user](http://lumen.lyyw.info/api/posts?include=user,comments.user) 帖子列表及发帖的用户和发帖的评论，及评论的用户信息
  - [http://lumen.lyyw.info/api/posts?include=user,comments:limit(1),comments.user](http://lumen.lyyw.info/api/posts?include=user,comments:limit(1),comments.user)  帖子列表及发帖的用户和发帖的1条评论，及评论的用户信息，及评论的用户信息


  是不是很强大，我们只需要提供资源，及资源之间的引用关系，省了多少事

</details>

<details>
  <summary>transformer 如何自定义格式化资源</summary>

dingo/api 使用了 [Fractal](http://fractal.thephpleague.com/) 做数据转换，fractal 提供了3种基础的序列化格式，Array，DataArray，JsonApi，在这里有详细的说明 [http://fractal.thephpleague.com/serializers/](http://fractal.thephpleague.com/serializers/)。DataArray 是默认的，也就是所有资源一定有data和meta。当然也可以按下面这样自定义：

        只需要在 bootstrap/app.php 中设置 serializer 就行了。具体见 bootstrap/app.php 有注释
        $app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new League\Fractal\Manager;
            // 自定义的和fractal提供的
            // $serializer = new League\Fractal\Serializer\JsonApiSerializer();
            $serializer = new League\Fractal\Serializer\ArraySerializer();
            // $serializer = new App\Serializers\NoDataArraySerializer();
            $fractal->setSerializer($serializer);,
            return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });

个人认为默认的 DataArray 就很好用了，基本满足了 API 的需求
</details>

<details>
  <summary>关于使用 repository </summary>

  首先不推荐大家使用 repository，已经将实现移动至 repository 分支。

  我对 repository 的理解是，它是一层对 orm 的封装，让 model 和 controller 层解耦，controller 只是关心增删该查什么数据，并不关心数据的操作是通过什么完成的，orm也好，DB也好，只要实现接口就好。而且封装了一层，我就可以对一些查询数据方便的进行缓存，而不需要调整 controller，非常方面，清晰。

  仓库不方便的地方就是对于普通的项目来说，切换 orm，或者抛弃 orm 转为全部使用 DB，基本上是不可能的，或者也是很后期优化的时候才会用到。还有就是，当一开始大家对 repository 的概念不清楚的时候，尝尝把大段的业务逻辑放在里面，而原本这些个业务逻辑应该出现在 controller 和 services 中。对我来说仓库的主要作用就是解耦和缓存, 而这些在项目初期是不需要的。

  所以一般的项目就直接使用 Eloquent 吧, 不要过度设计, 使用 ORM 是一件很方面的事情，dingo 的 transform 这一层就是通过 Eloquent 去预加载的。

  例子中使用的是 `rinvex/repository` 这个库。
</details>

<details>
  <summary>422 错误提示</summary>

  参考了 github 的错误提示，这样可能更方便 app 对接，格式固定有field 和code，field为字段名，code为错误提示。

  如果想用默认的，在 BaseController 中使用下面的代码即可
  `throw new ValidationHttpException($validator->errors());`
</details>

## TODO
- [ ] 单元测试

## 坑
- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`
- 如果 .env 的某个值中有空格会报错 log not found。env 中的值有空格需要引号包裹

## License

[MIT license](http://opensource.org/licenses/MIT)
