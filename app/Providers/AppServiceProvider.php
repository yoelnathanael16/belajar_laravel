<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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
        // Dipakai middleware "can:admin" di routes/web.php
        Gate::define('admin', fn (User $user) => $user->isAdmin());

        // @rupiah($angka) -> Rp1.480.000
        Blade::directive('rupiah', fn (string $angka) => "<?php echo 'Rp' . number_format({$angka}, 0, ',', '.'); ?>");

        // Angka di menu Pesanan pada sidebar admin
        View::composer('admin.layout', fn ($view) => $view->with(
            'jumlahMenunggu',
            Booking::where('status', 'menunggu')->count(),
        ));
    }
}