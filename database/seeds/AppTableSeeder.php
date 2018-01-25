<?php

use Illuminate\Database\Seeder;
use App\App;
class AppTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $app = new App();
        $app->client_id = '28850440375.28851830517';
        $app->client_secret = 'c552d1d0ef4a58c35f2cd55e27c3e3fb';
        $app->verification_token = '0pqhWvvTlTmT3AEr8wwqOWdu';
        $app->save();
    }
}
