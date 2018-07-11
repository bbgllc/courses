<?php

use App\Models\Auth\User;
use Illuminate\Database\Seeder;

/**
 * Class UserTableSeeder.
 */
class UserTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Add the master administrator, user id of 1
        User::create([
            'first_name'        => 'Admin',
            'last_name'         => 'Istrator',
            'username'          => 'admin',
            'affiliate_id'      => '988768',
            'email'             => 'admin@admin.com',
            'password'          => bcrypt('password'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed'         => true,
        ]);

        User::create([
            'first_name'        => 'John',
            'last_name'         => 'Doe',
            'username'          => 'johndoe',
            'affiliate_id'      => '455657',
            'email'             => 'author@author.com',
            'password'          => bcrypt('password'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed'         => true,
        ]);

        User::create([
            'first_name'        => 'Mary',
            'last_name'         => 'Jane',
            'username'          => 'mary',
            'affiliate_id'      => '987749',
            'email'             => 'user@user.com',
            'password'          => bcrypt('password'),
            'confirmation_code' => md5(uniqid(mt_rand(), true)),
            'confirmed'         => true,
        ]);

        $this->enableForeignKeys();
    }
}
