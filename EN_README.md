# lumen-api-demo

Forgive my poor English.

This is a demo for lumen5.3. if you are using lumen to write REST api it will help you.

This demo use `dingo/api`  `tymon/jwt-auth` and write some easy APIs and PHPUNIT

lumen5.1 look [here](https://github.com/liyu001989/lumen-api-demo/tree/5.1)

lumen5.2 look [here](https://github.com/liyu001989/lumen-api-demo/tree/5.2)

## USEFUL LINK

- dingo/api [https://github.com/dingo/api](https://github.com/dingo/api)
- json-web-token(jwt) [https://github.com/tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- transformer [fractal](http://fractal.thephpleague.com/)
- apidoc [apidocjs](http://apidocjs.com/)
- rest api guidance [jsonapi.org](http://jsonapi.org/format/)
- debug rest api [postman](https://www.getpostman.com/)
- a good article [http://oomusou.io/laravel/laravel-architecture](http://oomusou.io/laravel/laravel-architecture/)
- php lint [phplint](https://github.com/overtrue/phplint)
- my api doc [http://lumen.lyyw.info/apidoc](http://lumen.lyyw.info/apidoc)

## USAGE

```
$ git clone git@github.com:liyu001989/lumen-api-demo.git
$ composer install
$ cp .env.example .env
$ vim .env
        DB_*
            config  uration your database
	    JWT_SECRET
            php artisan jwt:secret
	    APP_KEY
            key:generate is abandoned in lumen, so do it yourself
            md5(uniqid())，str_random(32) etc.，maybe use jwt:secret and copy it

$ php artisan migrate
$ php artisan db:seed
$ generate api doc like this "apidoc -i App/Http/Controller/Api/v1 -o public/apidoc"
```

## REST API DESIGN

just a demo for rest api design

```
    demo： user, post, comment
    get    /api/posts              	 post index
    post   /api/posts              	 create a post
    get    /api/posts/5            	 post detail
    put    /api/posts/5            	 replace a post
    patch  /api/posts/5            	 update part of a post
    delete /api/posts/5            	 delete a post
    get    /api/posts/5/comments     comment list of a post
    post   /api/posts/5/comments     add a comment
    get    /api/posts/5/comments/8   comment detail
    put    /api/posts/5/comments/8   replace a comment
    patch  /api/posts/5/comments/8   update part of a comment
    delete /api/posts/5/comments/8   delete a comment
    get    /api/users/4/posts        post list of a user
    get    /api/user/posts           post list of current user
```

## FAQ

<details>
  <summary>lumen 5.1 upgrade to  5.2</summary>

- fix compose.json

  ```
    "laravel/lumen-framework": "5.2.*",
    "vlucas/phpdotenv": "~2.2" // important

    just compare composer.json in 5.2
  ```

- fix `bootstrap/app.php`

- `Illuminate\Contracts\Foundation\Application` changed to `Laravel\Lumen\Application`，so fix `app\providers\EventServiceProvider.php`

- cp Middleware to `app/Http/Middleware`
</details>

<details>
  <summary>about jwt</summary>

There is no session and auth guard in lumen 5.2, so attention `config/auth.php`. Also user model must implement `Tymon\JWTAuth\Contracts\JWTSubject`
</details>

<details>
 <summary>how to use mail</summary>

- composer require `illuminate/mail` and `guzzlehttp/guzzle`
- register email service in `bootstrap/app.php` or `some provider`
- add `mail.php` `services.php` in config, just copy them from laravel
- add `MAIL_DRIVER` in env
</details>

<details>
  <summary>how to user transformer </summary>

  transformer is a layer help you format you resource and their relationship.
  
  maybe you can knowstand with this links:

  - [https://lumen-new.lyyw.info/api/posts](https://lumen-new.lyyw.info/api/posts)
  - [https://lumen-new.lyyw.info/api/posts?include=user](https://lumen-new.lyyw.info/api/posts?include=user) 
  - [https://lumen-new.lyyw.info/api/posts?include=user,comments](https://lumen-new.lyyw.info/api/posts?include=user,comments)
  - [https://lumen-new.lyyw.info/api/posts?include=user,comments:limit(1)](https://lumen-new.lyyw.info/api/posts?include=user,comments:limit(1))
  - [https://lumen-new.lyyw.info/api/posts?include=user,comments.user](https://lumen-new.lyyw.info/api/posts?include=user,comments.user)
  - [https://lumen-new.lyyw.info/api/posts?include=user,comments:limit(1),comments.user](https://lumen-new.lyyw.info/api/posts?include=user,comments:limit(1),comments.user)

</details>

<details>
  <summary>transformer data serizlizer </summary>

dingo/api use [Fractal](http://fractal.thephpleague.com/) to transformer resouses，fractal provider 3 serializer,Array,DataArray,JsonApi.more details at here [http://fractal.thephpleague.com/serializers/](http://fractal.thephpleague.com/serializers/)。DataArray is default.You can set your own serizlizer like this：

        see bootstrap/app.php
        $app['Dingo\Api\Transformer\Factory']->setAdapter(function ($app) {
            $fractal = new League\Fractal\Manager;
            // $serializer = new League\Fractal\Serializer\JsonApiSerializer();
            $serializer = new League\Fractal\Serializer\ArraySerializer();
            // $serializer = new App\Serializers\NoDataArraySerializer();
            $fractal->setSerializer($serializer);,
            return new Dingo\Api\Transformer\Adapter\Fractal($fractal);
        });

I think default DataArray is good enough.
</details>

<details>
  <summary>repository</summary>

I achieved myself, there are two good repository packages you can try

`rinvex/repository`  and  `prettus/l5-repository`
</details>

## TODO

- [ ] phpunit

## carefully

- [https://github.com/dingo/api/issues/672](https://github.com/dingo/api/issues/672)  `transformer include`
- if there is a space in env file, it will throw `log not found ` error

## License

[MIT license](http://opensource.org/licenses/MIT)
