<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class CustomEmailConfig extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $host = DB::table('')->where('perimeter', 'host')->first();
        // $port = DB::table('settings')->where('perimeter', 'port')->first();
        // $from = DB::table('settings')->where('perimeter', 'from')->first();
        // $encryption = DB::table('settings')->where('perimeter', 'encryption')->first();
        // $username = DB::table('settings')->where('perimeter', 'username')->first();
        // $password = DB::table('settings')->where('perimeter', 'password')->first();

        // $config = array(
        //     'driver' => 'smtp',
        //     'host' => $host->value,
        //     'port' => $port->value,
        //     'from' => array('address' => $from->value, 'name' => env('APP_NAME')),
        //     'encryption' => $encryption->value,
        //     'username' => $username->value,
        //     'password' => $password->value,
        //     'sendmail' => '/usr/sbin/sendmail -bs',
        //     'pretend' => false,
        // );
        // \Config::set('mail', $config);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
