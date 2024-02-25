<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        'create_user',
        'update_user',
        'delete_user',
        'create_message',
        'update_message',
        'delete_message',
    ];

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $role = Role::create(['name' => 'user']);

        $role->givePermissionTo('create_message');

        $role = Role::create(['name' => 'admin']);

        foreach ($this->permissions as $permission) {
            $role->givePermissionTo($permission);
        }
    }
}
