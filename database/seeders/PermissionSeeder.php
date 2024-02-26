<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    private array $permissions = [
        'show_user',
        'create_user',
        'update_user',
        'delete_user',
        'show_message',
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
        $role->givePermissionTo('show_message', 'create_message');

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo($this->permissions);
    }
}
