<?php

namespace Database\Seeders;

use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class RandomUpdatedAtClients extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::all()->each(function ($client) {
            $client->update([
                'updated_at' => Carbon::today()->subDays(rand(1, 15)),
                'created_at' => Carbon::today()->subDays(rand(1, 15)),
            ]);
        });
    }
}
