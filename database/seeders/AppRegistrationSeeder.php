<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AppRegistration;

class AppRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppRegistration::factory(15)->create();
    }
}
