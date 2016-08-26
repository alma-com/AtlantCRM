<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Blade::directive('permission', function($expression) {
            return "<?php if (Auth::user()->access($expression)) : ?>";
        });

        \Blade::directive('endpermission', function($expression) {
            return "<?php endif;?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
