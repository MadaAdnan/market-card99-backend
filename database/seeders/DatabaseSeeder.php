<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Setting;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
//        $this->call(ServerSeeder::class);
//        $this->call(CountrySeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(AdminSeeder::class);
        Setting::create([
            'name'=>'mazen',
            'affiliate_ratio'=>0.05,
            'email'=>'admin@admin.com'
        ]);
    }
}
