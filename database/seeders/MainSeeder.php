<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = User::all();
        $faker = Factory::create();

        foreach ($users as $user) {
            for ($i = 0; $i <= 300; $i++) {
                Follower::create([
                    "name" => "RandomUser" . $i++,
                    "user_id" => $user->id
                ]);

                Subscriber::create([
                    "name" => "RandomUser" . $i++,
                    "subscription_tier" => "Tier" . rand(1,3),
                    "user_id" => $user->id
                ]);

                Donation::create([
                   "name" => "RandomUser" . $i++,
                   "amount" => rand(10,100),
                   "currency" => "USD",
                   "message"  => $faker->sentence,
                    "user_id" => $user->id
                ]);

               MerchSale::create([
                   "item_name" => $faker->word,
                   "amount"  => rand(1,10),
                   "price" => rand(10,60),
                   "user_id" => $user->id
               ]);

               sleep(1);
            }
        }

    }
}
