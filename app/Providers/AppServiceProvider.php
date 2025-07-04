<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    /*    View::composer('daily_records.edit', function ($view) {
            $view->with('budgetGoal', BudgetGoal::firstOrNew(['user_id' => auth()->id()]));
        }); */
    }
}
