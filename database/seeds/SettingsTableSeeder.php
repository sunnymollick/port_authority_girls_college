<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      Setting::create([
        'name' => 'Chittagong Port Women College',
        'slogan' => 'Knowledge is Power',
        'reg' => '12345',
        'stablished' => '1965',
        'email' => 'info@cpwc.edu.bd.com',
        'contact' => '01851334211',
        'address' => 'Bandhar,Chittagong',
        'website' => 'http://www.cpwc.w3schoolbd.org',
        'logo' => 'assets/images/logo/default.png',
        'favicon' => 'assets/images/logo/default_favicon.png',
        'layout' => '1',
        'running_year' => config('running_session'),
        'created_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d')
      ]);
   }
}
