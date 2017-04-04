<?php

use Illuminate\Database\Seeder;
use App\Model\AdminUser;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminUser::create([
            'mobile'   => '13826606509',
            'password' => bcrypt('benzun'),
            'nickname' => '本尊',
            'status'   => 1
        ]);
    }
}
