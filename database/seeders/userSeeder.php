<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class userSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create users first
        $user1 = User::create([
            'name' => 'Mohammad Reza',
            'lastname' => 'Mahdavi',
            'phone' => '1234567890',
            'address' => 'Afshar',
            'email' => 'mohammadrezamahdavi@gmail.com',
            'password' => Hash::make('123456'),
            'age' => 30,
            'user_id'=> null,
            'national_id_image' => 'images/national_ids/john_doe.jpg',
            'profile_image' => 'images/profiles/john_doe.jpg',
            'role_id' => $adminRole->id
        ]);
        // $user2 = User::create([
        //     'name' => 'Ali Ahmad',
        //     'lastname' => 'Ahmadi',
        //     'phone' => '0987654321',
        //     'address' => 'Kabul',
        //     'email' => 'ali@gmail.com',
        //     'password' => Hash::make('123456'),
        //     'age' => 25,
        //     'user_id'=> null,
        //     'national_id_image' => 'images/national_ids/jane_smith.jpg',
        //     'profile_image' => 'images/profiles/jane_smith.jpg',
        //     'role_id' => $userRole->id
        // ]);

        // $user1->update(['age_reference' => $user2->id]);
        // $user2->update(['age_reference' => $user1->id]);
    }
}
