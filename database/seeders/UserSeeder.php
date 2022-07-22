<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $data = [
            'name'       => 'เฟาซาน',
            'lastname'   => 'เยาะแต',
            'telephone'  => '0936925058',
            'avatar'     => 'https://via.placeholder.com/600x800.png/008876?text=society',
            'username'   => 'fauzan',
            'email'     => 'fauzanlk@gmail.com',
            'password' => Hash::make('70632537'),
            'remember_token' => Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
        User::create($data);
        User::factory(50)->create();
    }
}
