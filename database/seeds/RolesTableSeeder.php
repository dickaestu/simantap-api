<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'role_name' => 'admin',
            'seq'   => 0,
            'bagian_id' => 0,
            'keterangan' => 'admin'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 1,
            'bagian_id' => 1,
            'keterangan' => 'karo'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 1,
            'bagian_id' => 1,
            'keterangan' => 'karo'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 2,
            'bagian_id' => 2,
            'keterangan' => 'kasubag'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 2,
            'bagian_id' => 2,
            'keterangan' => 'kasubag'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 2,
            'bagian_id' => 3,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 2,
            'bagian_id' => 3,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 2,
            'bagian_id' => 4,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 2,
            'bagian_id' => 4,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 2,
            'bagian_id' => 5,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 2,
            'bagian_id' => 5,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 2,
            'bagian_id' => 6,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 2,
            'bagian_id' => 6,
            'keterangan' => 'kabag'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 3,
            'bagian_id' => 10,
            'keterangan' => 'kasubag pangkat'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 3,
            'bagian_id' => 10,
            'keterangan' => 'kasubag kompeten'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 3,
            'bagian_id' => 10,
            'keterangan' => 'kasubag mutjab'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 3,
            'bagian_id' => 10,
            'keterangan' => 'kasubag pangkat'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 3,
            'bagian_id' => 10,
            'keterangan' => 'kasubag kompeten'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 3,
            'bagian_id' => 10,
            'keterangan' => 'kasubag mutjab'
        ]);

        Role::create([
            'role_name' => 'sekretaris',
            'seq'   => 4,
            'bagian_id' => 10,
            'keterangan' => 'kaur'
        ]);

        Role::create([
            'role_name' => 'manager',
            'seq'   => 4,
            'bagian_id' => 10,
            'keterangan' => 'kaur'
        ]);
            
    }
}
