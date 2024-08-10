<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Propaganistas\LaravelPhone\PhoneNumber;


class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phones = json_decode(file_get_contents(__DIR__ .'/files/phones.json'));
        foreach ($phones as $row) {
            $normalize = str_replace('+', '', (string) new PhoneNumber($row->PhoneNumber));
            Client::create([
                'phone' => $normalize,
            ]);
        }
    }
}
