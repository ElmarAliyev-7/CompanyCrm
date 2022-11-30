<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientCompany extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all the companies attaching up to 3 random companies to each clients
        $companies = Company::all();

        // Populate the pivot table
        $clients = Client::all();
        $clients->each(function ($client) use ($companies) {
            $client->companies()->attach(
                $companies->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
