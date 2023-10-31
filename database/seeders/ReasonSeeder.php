<?php

namespace Database\Seeders;

use App\Models\Reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reason::factory()->create(['reason'=>'All Branch Meeting']);
        Reason::factory()->create(['reason'=>'Internal Meeting']);
        Reason::factory()->create(['reason'=>'With Supplier']);
    }
}
