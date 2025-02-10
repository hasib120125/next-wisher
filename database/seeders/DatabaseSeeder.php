<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(100)->create();

        \App\Models\User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'is_agree' => 0,
            'role' => 'admin',
        ]);

        \App\Models\Settings::create([
            'logo' => NULL, 
            'companyName' => 'Nextwisher', 
            'dateFormate' => 'MMMM Do YYYY', 
            'commission' => 20, 
            'currencyPosition' => 'left', 
            'stripePublicKey' => NULL, 
            'stripePrivatKey' => NULL, 
            'settings' => NULL, 
            'created_at' => '2023-03-15 20:37:46', 
            'updated_at' => '2023-03-21 19:51:11', 
            'maintenance_charge' => 5, 
            'request_duration_days' => 7
        ]);

        $this->call(CategorySeeder::class);
    }
}
