<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private $type = ['user', 'admin', 'talent'];

    public function definition()
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'username' => $name,
            // 'first_name' => '',
            // 'last_name' => '',
            // 'country' => '',
            // 'category_id' => '',
            // 'email' => '',
            // 'email_verified_at' => '',
            // 'password' => '',
            // 'link' => '',
            'video_path' => 'uploads/1676149441.mp4',
            'is_agree' => 1,
            'role' => $this->type[rand(0, count($this->type)-1)],
        ];
    }
    // array('id' => '1',
    // 'name' => 'Bert Oliver',
    // 'first_name' => NULL,
    // 'last_name' => NULL,
    // 'username' => NULL,'country' => NULL,'category_id' => NULL,'email' => 'admin@example.com','email_verified_at' => NULL,'password' => '$2y$10$OVOEO9UvJDhBihyaXVAk5epu3i67XCiGEjxa8XsX/rV3Te3GFDnH6','link' => NULL,'video_path' => NULL,'is_agree' => NULL,'remember_token' => NULL,'role' => 'admin','created_at' => '2023-02-11 17:43:42','updated_at' => '2023-02-11 17:43:42','deleted_at' => NULL)
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
