<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

use App\Models\Auth\User;
use App\Models\Payment;
use App\Models\Course;
use App\Models\Coupon;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        $users = User::all()->pluck('id')->toArray();
        $courses = Course::all()->pluck('id')->toArray();
        $coupons = Coupon::all()->pluck('id')->toArray();
        
        foreach(range(1,300) as $index){
            $price = $faker->numberBetween(10,100);
            
            $payment = Payment::create([
                'course_id' => $faker->randomElement($courses),
                'payer_id' => $faker->randomElement($users),
                'coupon_id' => $price < 30 ? NULL : $faker->randomElement($coupons),
                'payment_method' => 'credit-card',
                'amount' => $price,
                'author_earning' => 0.42*$price,
                'description' => 'Purchase',
                'created_at' => $faker->dateTimeBetween($startDate = '-4 month', $endDate = 'now')
            ]);
        }
    }
    
}
