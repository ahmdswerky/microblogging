<?php

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
        factory(User::class)->create(['email' => 'ahmdswerky@gmail.com']);

        factory(User::class)->create(['email' => 'admin@ibtikar.net.sa']);
    }
}
