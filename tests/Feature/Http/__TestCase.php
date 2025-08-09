<?php

namespace Tests\Feature\Http;

use App\Models\Moodle\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class __TestCase extends TestCase
{
    use DatabaseTransactions;
    /**
     * An admin user.
     */
    protected User $admin;

    protected array $connectionsToTransact = ['mysql', 'mysql_moodle'];

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
//        $this->seed(PermissionsTableSeeder::class);
//        $this->seed(RolesTableSeeder::class);
    }




}
