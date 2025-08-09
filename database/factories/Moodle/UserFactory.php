<?php

namespace Database\Factories\Moodle;

use App\Enums\MoodleRoleEnum;
use App\Enums\RoleEnum;
use App\Models\Moodle\Student;
use App\Models\Moodle\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Moodle\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'firstname' => fake()->firstName(),
            'middlename' => fake()->firstName(),
            'lastname' => fake()->lastname(),
            'idnumber' => fake()->creditCardNumber(),
            'email' => fake()->unique()->safeEmail(),
            'phone1' => fake()->phoneNumber(),
            'phone2' => fake()->phoneNumber(),
            'lastip' => fake()->ipv4(),
            'address' => fake()->address(),
            'city' => fake()->city(),
//            'country' => Faker::state(),
            'description' => fake()->jobTitle(),
            'password' =>//Admin1234!@#$
                '$2y$10$Hov8lIrhgEwp32hN7oS8n.i9W9EV2iwTzN.ti34zfIGtuExcfO8ye',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * @return UserFactory
     */
    public function admin(): UserFactory
    {
        Role::query()->firstOrCreate([
            'name' => RoleEnum::ADMIN->name,
            'guard_name'=>'api'
        ]);
        return $this
            ->afterCreating(function (User $user) {
                DB::connection("mysql_moodle")
                    ->table('role_assignments')->insert(
                        [
                            'roleid' => MoodleRoleEnum::ADMIN->value,
                            'userid' => $user->id,
                            'contextid' => '14'
                        ]
                    );
                $user->assignRole(RoleEnum::ADMIN->name);
            });
    }

    public function supervisor(): UserFactory
    {
        Role::query()->firstOrCreate([
            'name' => RoleEnum::SUPERVISOR->name,
            'guard_name'=>'api'
        ]);
        return $this
            ->afterCreating(function (User $user) {
                DB::connection("mysql_moodle")
                    ->table('role_assignments')->insert(
                        [
                            'roleid' => MoodleRoleEnum::ADMIN->value,
                            'userid' => $user->id,
                            'contextid' => '14'
                        ]
                    );
                $user->assignRole(RoleEnum::SUPERVISOR->name);
            });
    }

    public function teacher(): UserFactory
    {
        Role::query()->firstOrCreate([
            'name' => RoleEnum::TEACHER->name,
            'guard_name'=>'api'
        ]);
        return $this
            ->afterCreating(function (User $user) {
                DB::connection("mysql_moodle")
                    ->table('role_assignments')->insert(
                        [
                            'roleid' => MoodleRoleEnum::TEACHER->value,
                            'userid' => $user->id,
                            'contextid' => '14'
                        ]
                    );
                $user->assignRole(RoleEnum::TEACHER->name);
            });
    }
}
