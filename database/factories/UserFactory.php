<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $avatar = $this->faker->randomElement([
            'https://res.cloudinary.com/dvbcjs57y/image/upload/f33eea8a-3725-42f9-b270-cde36e0930ce.png',
            'https://res.cloudinary.com/dvbcjs57y/image/upload/f27cad24-c09c-49d1-bb09-c1eae354e5d2.png',
            'https://res.cloudinary.com/dvbcjs57y/image/upload/c82e58b9-d15e-40e3-9251-4c6db8459f31.png',
            'https://res.cloudinary.com/dvbcjs57y/image/upload/85ded3ef-b6dc-47ff-9a5f-ce2770999125.png',
            'https://res.cloudinary.com/dvbcjs57y/image/upload/aef5c478-5503-4ab6-ac73-dc282f5bf378.png',
            'https://res.cloudinary.com/dvbcjs57y/image/upload/817b8289-4163-4713-851d-0e90c505062c.png',
        ]);

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'birthday' => $this->faker->date(),
            'avatar' => $avatar,
        ];
    }
}
