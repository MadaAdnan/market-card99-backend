<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

       /* $role=Role::create([
            'name'=>'super_admin',
            'display_name'=>'المدير العام',
            'guard_name'=>'web',
        ]);

        Role::create([
            'name'=>'partner',
            'display_name'=>'وكيل',
            'guard_name'=>'web',
        ]);*/
        $user=User::create([
            'phone'=>'09887654',
            'name'=>'admin',
            'email'=>'admin@admin.com',
            'address'=>'sarmada',
            'username'=>'admin',
            'password'=>bcrypt('password'),
            'group_id'=>1,
            'device_token'=>'',
        ]);

        //$user->roles()->sync(1);
    }
}
