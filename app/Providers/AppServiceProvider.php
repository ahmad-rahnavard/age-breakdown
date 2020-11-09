<?php

namespace App\Providers;

use Illuminate\Http\Response;
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
        Response::macro('success',
            fn(string $message, array $data = [], int $status = 200) => response()->json([
                'status'  => $status,
                'message' => $message,
                'data'    => $data
            ], $status));

        Response::macro('error',
            fn(string $message, array $errors = [], int $status = 200) => response()->json([
                'status'  => $status,
                'message' => $message,
                'errors'  => $errors
            ], $status));
    }
}
