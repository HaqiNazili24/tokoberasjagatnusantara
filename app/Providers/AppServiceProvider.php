<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            try {
                if (auth()->check() && auth()->user()->isCustomer()) {
                    $cartCount = Cart::where('user_id', auth()->id())->sum('quantity');
                }
            } catch (\Throwable $e) {
                $cartCount = 0;
            }
            $view->with('globalCartCount', $cartCount);
        });
    }
}
