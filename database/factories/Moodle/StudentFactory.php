<?php

namespace Database\Factories\Moodle;

use App\Enums\MoodleRoleEnum;
use App\Enums\RoleEnum;
use App\Models\Moodle\MdlRole;
use App\Models\Moodle\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Ybazli\Faker\Facades\Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Moodle\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
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
        ];
    }


    /**
     * @return StudentFactory
     */
    public function student(): StudentFactory
    {

        MdlRole::query()->firstOrCreate(
            [
                'id' => MoodleRoleEnum::STUDENT->value
            ],
            [
                'shortname' => strtolower(MoodleRoleEnum::STUDENT->name),
                'description' => MoodleRoleEnum::STUDENT->value,
            ]);
        return $this
            ->afterCreating(function (Student $student) {
                DB::connection("mysql_moodle")
                    ->table('role_assignments')->insert(
                        [
                            'roleid' => MoodleRoleEnum::STUDENT->value,
                            'userid' => $student->id,
                            'contextid' => '14'
                        ]
                    );
            });
    }

}
