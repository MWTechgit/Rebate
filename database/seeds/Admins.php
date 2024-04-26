<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Admins extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'role'           => 'admin',
            'first_name'     => 'Justin',
            'last_name'      => 'Tallant',
            'full_name'      => null,
            'email'          => 'jtallant07@gmail.com',
            'company'        => 'Example Company Name',
            'phone'          => null,
            'password'       => bcrypt('password'),
            'remember_token' => Str::random(10)
        ]);

        Admin::create([
            'role'           => 'admin',
            'first_name'     => 'Samantha',
            'last_name'      => 'Baker',
            'full_name'      => null,
            'email'          => 'stbaker@broward.org',
            'company'        => 'Broward',
            'phone'          => null,
            'password'       => bcrypt('stbaker@broward.org'),
            'remember_token' => Str::random(10)
        ]);

        Admin::create([
            'role'           => 'admin',
            'first_name'     => 'Erin',
            'last_name'      => 'Lambroschini',
            'full_name'      => null,
            'email'          => 'erinlambro@gmail.com',
            'company'        => 'Broward',
            'phone'          => null,
            'password'       => bcrypt('password'),
            'remember_token' => Str::random(10)
        ]);

        Admin::create([
            'role'           => 'call_center',
            'first_name'     => 'Call',
            'last_name'      => 'Center',
            'full_name'      => null,
            'email'          => 'cc@example.com',
            'company'        => 'Call Center',
            'phone'          => null,
            'password'       => bcrypt('password'),
            'remember_token' => Str::random(10)
        ]);

        factory(Admin::class, 10)->create();
    }
}
