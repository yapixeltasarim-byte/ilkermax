<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ilkermax:setup-admin')]
#[Description('.env\'deki FILAMENT_ADMIN_EMAIL/PASSWORD ile ilk admin kullanıcısını oluşturur (yoksa).')]
class SetupAdmin extends Command
{
    public function handle(): int
    {
        $email = env('FILAMENT_ADMIN_EMAIL');
        $password = env('FILAMENT_ADMIN_PASSWORD');

        if (! $email || ! $password) {
            $this->warn('FILAMENT_ADMIN_EMAIL / FILAMENT_ADMIN_PASSWORD .env\'de tanımlı değil, admin kullanıcı oluşturulmadı.');

            return self::SUCCESS;
        }

        if (User::where('email', $email)->exists()) {
            $this->info("Admin kullanıcı zaten var: {$email}");

            return self::SUCCESS;
        }

        $admin = User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => $password,
        ]);
        $admin->forceFill(['email_verified_at' => now()])->save();

        $this->info("Admin kullanıcı oluşturuldu: {$email}");

        return self::SUCCESS;
    }
}
