<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Patient::factory(10)->create();
    }
}
