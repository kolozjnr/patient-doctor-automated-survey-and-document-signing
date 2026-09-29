<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Module;
use App\Enums\RoleEnum;
use App\Models\ClientAdmin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modules = [
            'users' => [
                'actions' => [
                    'index' => 'user.index',
                    'create'  => 'user.create',
                    'edit'    => 'user.edit',
                    'trash' => 'user.destroy',
                    'restore' => 'user.restore',
                    'delete' => 'user.forceDelete'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'trash', 'restore', 'destroy'],
                ]
            ],
            'roles' => [
                'actions' => [
                    'index'   => 'role.index',
                    'create'  => 'role.create',
                    'edit'    => 'role.edit',
                    'delete'  => 'role.destroy'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'delete'],
                ],
            ],
            'attachments' => [
                'actions' => [
                    'index'   => 'attachment.index',
                    'create'  => 'attachment.create',
                    'delete'  => 'attachment.destroy'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'delete'],
                    RoleEnum::PATIENT => ['index', 'create', 'delete'],
                    RoleEnum::EMPLOYEE => ['index', 'create', 'delete'],
                    RoleEnum::DEV => ['index', 'create', 'delete'],
                ],
            ],
            'categories' => [
                'actions' => [
                    'index'   => 'category.index',
                    'create'  => 'category.create',
                    'edit'    => 'category.edit',
                    'delete'  => 'category.destroy'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'delete'],
                    RoleEnum::USER => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::PATIENT => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::EMPLOYEE => ['index', 'create', 'edit', 'destroy'],
                    RoleEnum::DEV => ['index', 'create', 'edit', 'destroy'],
                ]
            ],
            'tags' => [
                'actions' => [
                    'index'   => 'tag.index',
                    'create'  => 'tag.create',
                    'edit'    => 'tag.edit',
                    'trash'   => 'tag.destroy',
                    'restore' => 'tag.restore',
                    'delete'  => 'tag.forceDelete'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'trash', 'restore', 'delete'],
                    RoleEnum::USER => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::PATIENT => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::EMPLOYEE => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::DEV => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                ]
            ],
            'blogs' => [
                'actions' => [
                    'index'   => 'blog.index',
                    'create'  => 'blog.create',
                    'edit'    => 'blog.edit',
                    'trash'   => 'blog.destroy',
                    'restore' => 'blog.restore',
                    'delete'  => 'blog.forceDelete'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'trash', 'restore', 'delete'],
                    RoleEnum::USER => ['index', 'create', 'edit', 'forceDelete'],
                    RoleEnum::PATIENT => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::EMPLOYEE => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::DEV => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                ]
            ],
            'pages' => [
                'actions' => [
                    'index'   => 'page.index',
                    'create'  => 'page.create',
                    'edit'    => 'page.edit',
                    'trash'   => 'page.destroy',
                    'restore' => 'page.restore',
                    'delete'  => 'page.forceDelete'
                ],
                'roles' => [
                    RoleEnum::ADMIN => ['index', 'create', 'edit', 'trash', 'restore', 'delete'],
                    RoleEnum::USER => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::PATIENT => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::EMPLOYEE => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                    RoleEnum::DEV => ['index', 'create', 'edit', 'destroy', 'restore', 'forceDelete'],
                ]
            ],
        ];

        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $userpermision = [];
        $patientpermision = [];
        $employeepermision = [];
        $memberpermision = [];
        $creatorpermision = [];

        foreach ($modules as $key => $value) {
            Module::updateOrCreate(['name' => $key], ['name' => $key, 'actions' => $value['actions']]);
            foreach ($value['actions'] as $actionKey => $permissionName) {
                // ADMIN permission
                $adminPermission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'admin',
                ]);

                // WEB permission
                $webPermission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                ]);

                foreach ($value['roles'] as $role => $allowed_actions) {

                    if (!in_array($actionKey, $allowed_actions)) {
                        continue;
                    }

                    switch ($role) {
                        case RoleEnum::USER:
                            $userpermision[] = $webPermission;
                            break;

                        case RoleEnum::PATIENT:
                            $patientpermision[] = $webPermission;
                            break;

                        case RoleEnum::EMPLOYEE:
                            $employeepermision[] = $webPermission;
                            break;

                        case RoleEnum::DEV:
                            $creatorpermision[] = $webPermission;
                            break;
                    }
                }
            }

        }


        $admin = Role::create([
            'name' => RoleEnum::ADMIN,
            'system_reserve' => true,
            'guard_name' => 'admin'
        ]);
        $adminPermissions = Permission::where('guard_name', 'admin')->get();
        $admin->syncPermissions($adminPermissions);
        $user = ClientAdmin::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'country_code' => '49',
            'phone' => '0891234567',
            'password' => Hash::make('123456789'),
        ]);
        $user->assignRole($admin);

        $userRole = Role::create([
            'name' => RoleEnum::USER,
            'system_reserve' => false,
            'guard_name' => 'web'
        ]);

        $webPermissions = Permission::where('guard_name', 'web')->get();
        $userRole->syncPermissions($webPermissions);

        $user = User::factory()->create([
            'first_name' => 'Quinn',
            'email' => 'quinn@example.com',
            'patient_id' => '01',
            'system_reserve' => false,
        ]);
        $user->assignRole($userRole);
        $image = public_path('/assets/images/user-images/1.png');
        if (File::exists($image)) {
            $user->addMedia($image)->toMediaCollection('image');
        }

        $patientRole = Role::create([
            'name' => RoleEnum::PATIENT,
            'system_reserve' => false,
            'guard_name' => 'web'
        ]);
        
        $webPermissions = Permission::where('guard_name', 'web')->get();
        $patientRole->syncPermissions($webPermissions);
        $user = User::factory()->create([
            'first_name' => 'Quinn',
            'email' => 'quinn1@example.com',
            'patient_id' => '02',
            'system_reserve' => false,
        ]);
        $user->assignRole($patientRole);
        $image = public_path('/assets/images/user-images/2.png');
        if (File::exists($image)) {
            $user->addMedia($image)->toMediaCollection('image');
        }

       

        $EmployeeRole = Role::create([
            'name' => RoleEnum::EMPLOYEE,
            'system_reserve' => false,
            'guard_name' => 'web'
        ]);
        $webPermissions = Permission::where('guard_name', 'web')->get();
        $EmployeeRole->syncPermissions($webPermissions);
        $user = User::factory()->create([
            'first_name' => 'Sierra',
            'last_name' => 'Carson',
            'email' => 'buloqo@mailinator.com',
            'patient_id' => '03',
            'system_reserve' => false,
        ]);
        $user->assignRole($EmployeeRole);
        $image = public_path('/assets/images/user-images/4.png');
        if (File::exists($image)) {
            $user->addMedia($image)->toMediaCollection('image');
        }

        $devRole = Role::create([
            'name' => RoleEnum::DEV,
            'system_reserve' => false,
            'guard_name' => 'web'
        ]);
        $webPermissions = Permission::where('guard_name', 'web')->get();
        $devRole->syncPermissions($webPermissions);
        $user = User::factory()->create([
            'first_name' => 'Phyllis',
            'last_name' => 'Berg',
            'email' => 'xubefymumi@mailinator.com',
            'patient_id' => '04',
            'system_reserve' => false,
        ]);
        $user->assignRole($devRole);
        $image = public_path('/assets/images/user-images/5.png');
        if (File::exists($image)) {
            $user->addMedia($image)->toMediaCollection('image');
        }
    }
}
