<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\BayarIpaymu;
use Illuminate\Support\Facades\View;

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
        //
         View::composer('*', function ($view) {
        $unreadPayments = BayarIpaymu::where('sts_bayar', 2)
                        ->whereHas('transaction', function ($q) {
                            $q->where('status', 'success');
                        })
                        ->count();

        $view->with('ipaymuPendingCount', $unreadPayments);
    });
    }
}
