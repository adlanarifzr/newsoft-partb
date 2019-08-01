<?php

use App\Listing;
use Illuminate\Database\Seeder;

class ListingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Listing::create([
	        'list_name' => 'Starbucks @ Mid Valley Megamall',
	        'address' => 'Linkaran Syed Putra, Mid Valley City',
	        'latitude' => 3.117880,
	        'longitude' => 101.676749,
	        'submitter_id' => 2,
        ]);
    }
}
