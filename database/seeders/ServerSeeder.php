<?php

namespace Database\Seeders;

use App\Models\Server;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Server::create([
            'name'=>'server1',
            'type'=>'json',
            'code'=>'pow',
        ]);

        Server::create([
            'name'=>'server2',
            'type'=>'json',
            'code'=>'sim',
        ]);
    }
}
