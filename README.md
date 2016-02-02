# lumen-api-demo

lumen 5.2 出来了, 不升级心理不舒服

dingoapi 是不是没人维护了？用lumen就是为了速度，就不引入这些个包了，尝试着实现一些dingo比较好的东西吧

## lumen 5.1 升级 5.2

- 先修改compose.json 中的依赖
    
        "laravel/lumen-framework": "5.2.*",
        "vlucas/phpdotenv": "~2.2" // 这是个坑啊

        直接将5.2的composer.json拿来替换了

- 修改bootstrap/app.php，照着改
- Illuminate\Contracts\Foundation\Application 改为了Laravel\Lumen\Application，所以修改一下app\providers\EventServiceProvider.php
- 把Middleware cp过来

## TODO
- 研究一下5.2 的认证
- 规范一下返回值得数据结构
- 增加一些返回http返回trait
- 引入fractal，感觉这个还是挺好用的

