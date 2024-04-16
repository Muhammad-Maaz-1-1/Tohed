<?php

namespace Database\Seeders;

use App\Models\masjidModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Testing\Fakes\Fake;

class masjidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
     {
        //
        $faker = Faker::create('ur_PK'); // Set Faker locale to Urdu (Pakistan)

        foreach (range(1, 10) as $index) {
            masjidModel::create([
                'name' => $faker->name,
                'masjid_name' => $faker->name,
                'masjid_address' => $faker->address,
                'city' => $faker->city,
                'country' => $faker->country,
                'imam_name' => $faker->name,
                'khateeb_name' => $faker->name,
                'images' => $faker->imageUrl(),
                'contact_number' => $faker->phoneNumber,
                'heading' => $faker->sentence,
                'content' => $faker->paragraph,
                'status' => $faker->boolean,
            ]);
        }
      }
    }
  