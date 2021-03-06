<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'Humayun Kobir',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('112233'),
        ]);

        $faker = Factory::create();
        foreach (range(1, 5) as $item) {
            User::create([
                'name'     => $faker->name,
                'email'    => $faker->email,
                'password' => Hash::make('123123'),
            ]);
        }
    }
}
