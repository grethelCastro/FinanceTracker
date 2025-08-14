<?php

namespace App\Providers;
use App\Models\Transaction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    

    /**
     * Bootstrap any application services.
     */
public function boot(): void
{
    Transaction::observe(\App\Observers\TransactionObserver::class);
    Schema::defaultStringLength(191); // 191 * 4 bytes = 764 bytes < 1000
}
}
