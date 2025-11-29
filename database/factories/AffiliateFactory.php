<?php

namespace Database\Factories;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AffiliateFactory extends Factory
{
    protected $model = Affiliate::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'referral_code' => $this->faker->unique()->bothify('REF-####'),
            'commission_rate' => $this->faker->randomFloat(2, 5, 15),
            'status' => 'active',
        ];
    }
}
