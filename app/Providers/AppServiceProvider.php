<?php

namespace App\Providers;

use App\Models\Tag;
use App\Transformers\TagTransformer;
use Dingo\Api\Transformer\Factory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        app(Factory::class)->register(Tag::class,TagTransformer::class);
    }
}
