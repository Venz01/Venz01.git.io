<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed or update the default admin account.
     */
    public function run(): void
    {
        $name = env('ADMIN_NAME', 'System Administrator');
        $email = env('ADMIN_EMAIL', 'admin@catering.local');
        $password = env('ADMIN_PASSWORD', 'Venz!Admin#2026$R7q');

        $admin = User::firstOrNew(['email' => $email]);
        $isNew = ! $admin->exists;

        if ($isNew) {
            $admin->password = Hash::make($password);
        }

        $admin->name = $name;
        $admin->role = User::ROLE_ADMIN;
        $admin->status = 'approved';
        $admin->email_verified_at = $admin->email_verified_at ?? now();
        $admin->save();

        $this->command?->info(
            $isNew
                ? "Admin account created: {$email}"
                : "Admin account verified/updated: {$email}"
        );
    }
}
