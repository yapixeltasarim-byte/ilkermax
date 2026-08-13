<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PropertyController;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/ilanlar', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/ilan/{property:slug}', [PropertyController::class, 'show'])->name('properties.show');

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create()
        ->add(Url::create(route('home'))->setPriority(1.0))
        ->add(Url::create(route('properties.index'))->setPriority(0.8));

    Property::published()->select('slug', 'updated_at')->each(function (Property $property) use ($sitemap) {
        $sitemap->add(
            Url::create(route('properties.show', $property))
                ->setLastModificationDate($property->updated_at)
                ->setPriority(0.6)
        );
    });

    return $sitemap->toResponse(request());
})->name('sitemap');

/*
|--------------------------------------------------------------------------
| Tek seferlik, SSH gerektirmeyen kurulum route'u
|--------------------------------------------------------------------------
| Paylaşımlı hosting'de terminal erişimi olmadan migrate + seed + admin
| kullanıcı oluşturmak için. .env'deki DEPLOY_SECRET ile korunur.
| Kullandıktan sonra .env'den DEPLOY_SECRET'ı silin (route otomatik kapanır).
*/
Route::get('/_deploy/{token}', function (string $token) {
    abort_unless(
        config('services.deploy_secret') && hash_equals((string) config('services.deploy_secret'), $token),
        404
    );

    $output = [];

    Artisan::call('migrate', ['--force' => true]);
    $output[] = 'migrate: '.trim(Artisan::output());

    Artisan::call('storage:link');
    $output[] = 'storage:link: '.trim(Artisan::output());

    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\ProductionSeeder', '--force' => true]);
    $output[] = 'seed: '.trim(Artisan::output());

    $adminEmail = env('FILAMENT_ADMIN_EMAIL');
    $adminPassword = env('FILAMENT_ADMIN_PASSWORD');

    if ($adminEmail && $adminPassword && ! User::where('email', $adminEmail)->exists()) {
        $admin = User::create([
            'name' => 'Admin',
            'email' => $adminEmail,
            'password' => $adminPassword,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();
        $output[] = "Admin kullanıcı oluşturuldu: {$adminEmail}";
    } else {
        $output[] = 'Admin kullanıcı oluşturulmadı (FILAMENT_ADMIN_EMAIL/PASSWORD eksik veya kullanıcı zaten var).';
    }

    return response(
        "Kurulum tamamlandı.\n\n".implode("\n\n", $output).
        "\n\nGÜVENLİK: Şimdi .env dosyasından DEPLOY_SECRET satırını silin.",
        200
    )->header('Content-Type', 'text/plain; charset=utf-8');
});
