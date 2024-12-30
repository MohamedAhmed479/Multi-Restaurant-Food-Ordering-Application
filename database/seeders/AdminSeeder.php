<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new Admin();
        
        $admin->name = 'Admin';
        $admin->email = 'Admin@admin.com';
        $admin->password = Hash::make('12345678');
        $admin->save();

    }
}
