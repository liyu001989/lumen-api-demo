# lumen-api-demo

一个用lumen写api的例子

## 相关文档
- 使用 [dingo/api](https://github.com/dingo/api)
- 用户验证使用 [jwt(json-web-token)](https://github.com/tymondesigns/jwt-auth)
- orm transformer [fractal](http://fractal.thephpleague.com/)
- 文档使用 [apidocjs](http://apidocjs.com/)
- api规范参考 [jsonapi.org](http://jsonapi.org/format/)

##使用dingo API
教程 [http://vea.re/blog/150905-api-with-dingo-and-lumen-part-1](http://vea.re/blog/150905-api-with-dingo-and-lumen-part-1)

lumen 关闭了好多功能，所以要先修改一下才能使用

大概步骤是:

1. 修改bootstrap/app.php，打开 `Dotenv::load(__DIR__.'/../')`,这样就可以加载.env文件了
2. 打开 `$app->withEloquent()`，因为要使用orm
3. 注册dingo的服务 `$app->register(Dingo\Api\Provider\LumenServiceProvider::class)`,添加到80行左右那个位置。
4. 然后就可以按照dingo的文档使用了

		$api = app('Dingo\Api\Routing\Router');

		$api->version('v1', function($api){
		    $api->get('collections/{collection}', function(){
		        return 'test';
		    });
		});

### 使用问题
1. 如何使用不同版本的api

再header中增加Accept

Accept: application`API_STANDARDS_TREE`.`API_SUBTYPE`.`VERSION`+json

	例如：Accept: application/prs.nagehao.v2+json

## 使用jwt
教程 [http://laravelista.com/json-web-token-authentication-for-lumen/
](http://laravelista.com/json-web-token-authentication-for-lumen/
)

这个库`tymondesigns/jwt-auth`图片的大标题写着 for laravel & lumen
但是！！还没有支持lumen，所以要按照上面的教程一步一步做很多工作。
作者说很快会跟新lumen的文档

[https://github.com/generationtux/jwt-artisan](https://github.com/generationtux/jwt-artisan) 这个库支持了lumen, 已尝试，可以用, 我还是决定用tymondesigns的这个。

## 坑
- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`