<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->name = "Menno";
        $user->email = "spijkermenno@gmail.com";
        $user->email_verified_at = now();
        $user->password = '$2y$10$ruI2wdwSWDIKqy3kutblPenge4CBeTFP9qsdT9pDTTC/eU4P/7yoe'; // test1234_
        $user->save();
        $user->generateToken();

    }
}
