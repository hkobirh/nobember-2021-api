<?php

namespace Database\Seeders;

use App\Models\Book;
use Faker\Factory;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $faker = Factory::create();
        foreach (range(1,500) as $item) {
            Book::create([
                'title' => substr($faker->name,20,120),
                'author' => $faker->name,
                'publisher' =>  $faker->name,
                'edition' =>  $faker->name,
                'country' => $faker->country,
                'price' => rand(99,199),
                'image' => $faker->imageUrl,
                'create_by' => rand(1,20),
            ]);

       }
    }
}
