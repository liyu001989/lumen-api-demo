<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {


        $this->app->singleton('mailer', function ($app) {
            $app->configure('services');

            return $app->loadComponent('mail', 'Illuminate\Mail\MailServiceProvider', 'mailer');
        });

        // set locale
        $request = app('request');
        $acceptLanguage = $request->header('accept-language');
        if ($acceptLanguage) {
            $language = current(explode(',', $acceptLanguage));
            app('translator')->setLocale($language);
        }

        // cors 增加对options的处理
        if ($request->isMethod('OPTIONS')) {
            app()->options($request->path(), function() {
                $headers = config('cors');
                return response('', 200, $headers);
            });
        }
        /*
         * 有些情况，比如angularjs调用后端程序并跳转到第三方的时候
         * 比如要去第三方登录或第三方付款。需要新开窗口，那么因为浏览器安全限制
         * 无法拿到服务器返回的302 再新开窗口跳转
         * 所以只能通过form post新开窗口的方式跳转，这个时候好像没办法设置header
         *
         * 所以有些时候jwt 的token需要放在url中,所以手动处理一下
         */
        if ($token = $request->get('authorization')) {
            $request->headers->set('Authorization', 'Beare '.$token);
        }
    }
}
