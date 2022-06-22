<?php

namespace App\Providers;

use App\Traits\DateTime;
use Illuminate\Support\Facades\Blade as Facade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    use DateTime;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Facade::directive('date', function ($expression) {
            return "<?php echo company_date($expression); ?>";
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
