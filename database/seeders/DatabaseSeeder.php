<?php

namespace Database\Seeders;

use App\Models\Flavor;
use Carbon\Carbon;
use App\Models\Supplement;
use Database\Factories\FlavorFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = DB::table('users')->insertGetId([
            'name' => 'dorian',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin')
        ]);
        $user = DB::table('users')->insert([
            'name' => 'antonin',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user')
        ]);

        $roleAdmin = DB::table('roles')->insertGetId([
            'name' => 'admin'
        ]);
        $roleUser = DB::table('roles')->insertGetId([
            'name' => 'user'
        ]);
        DB::table('role_user')->insert([
            'role_id' => $roleAdmin,
            'user_id' => $admin
        ]);
        DB::table('role_user')->insert([
            'role_id' => $roleUser,
            'user_id' => $admin
        ]);

        $nappage_id = DB::table('supplement_types')->insert([
            'name' => 'nappage',
            'price' => 30,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $toppin_id = DB::table('supplement_types')->insertGetId([
            'name' => 'toppin',
            'price' => 150,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $coconut = DB::table('supplements')->insertGetId([
            'name' => 'coconut shard',
            'weight' => 5,
            'unit' => 'g',
            'supplement_type_id' => $toppin_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $cookie = DB::table('supplements')->insertGetId([
            'name' => 'coconut shard',
            'weight' => 1,
            'unit' => 'u',
            'supplement_type_id' => $toppin_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $caramel = DB::table('supplements')->insertGetId([
            'name' => 'caramel',
            'weight' => 3.5,
            'unit' => 'cl',
            'supplement_type_id' => $nappage_id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        $flavor = DB::table('flavors')->insertGetId([
            'name' => 'vanilla',
            'color' => 'yellow',
            'price' => 250,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);


        $cream1 = DB::table('classic_creams')->insertGetId([
            'name' => 'first classix',
            'flavor_id' => $flavor,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);



        $cream0 = DB::table('custom_creams')->insertGetId([
            'name' => 'coco caramel',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $pivot1 = DB::table('custom_cream_flavor')->insertGetId([
            'flavor_id' => $flavor,
            'custom_cream_id' => $cream0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $pivot2 = DB::table('custom_cream_supplement')->insertGetId([
            'supplement_id' => $coconut,
            'custom_cream_id' => $cream0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $pivot3 = DB::table('custom_cream_supplement')->insertGetId([
            'supplement_id' => $caramel,
            'custom_cream_id' => $cream0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        $cart = DB::table('carts')->insertGetId([
            'state' => 0,
            'user_id' => $admin
        ]);

        DB::table('cart_custom_cream')->insertGetId([
            'cart_id' => $cart,
            'custom_cream_id' => $cream0
        ]);
        DB::table('cart_classic_cream')->insertGetId([
            'cart_id' => $cart,
            'classic_cream_id' => $cream1
        ]);


        Flavor::factory()
            ->count(500)
            ->create();
    }
}
