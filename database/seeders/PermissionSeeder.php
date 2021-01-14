<?php

namespace Database\Seeders;

use Illuminate\Cache\CacheManager;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

/**
 * 权限 seeder
 * https://spatie.be/docs/laravel-permission/v3/basic-usage/new-app
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        // php artisan cache:forget spatie.permission.cache 清除缓存
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $tableNames = config('permission.table_names');
        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        // 先截断表
        DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // 禁用外键约束
        DB::table($tableNames['role_has_permissions'])->truncate();
        DB::table($tableNames['model_has_roles'])->truncate();
        DB::table($tableNames['model_has_permissions'])->truncate();
        DB::table($tableNames['roles'])->truncate();
        DB::table($tableNames['permissions'])->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // 启用外键约束

        $permissions = [
            [
                'name'       => 'home',
                'path'       => '/',
                'icon'       => 'dashboard',
                'title'      => '主页',
                'component'  => 'Layout',
                'breadcrumb' => 0,
                'hidden'     => 0,
                'noCache'    => 0,
                'redirect'   => '/dashboard',
                'activeMenu' => '',
                'child' => [
                    [
                        'name'       => 'dashboard',
                        'path'       => 'dashboard',
                        'icon'       => 'dashboard',
                        'title'      => '仪表盘',
                        'component'  => 'dashboard/index',
                        'breadcrumb' => 0,
                        'hidden'     => 0,
                        'noCache'    => 0,
                        'redirect'   => '',
                        'activeMenu' => '',
                        'child' => [

                        ]
                    ]
                ]
            ],
            [
                'name'       => 'system',
                'path'       => '/system',
                'icon'       => 'system',
                'title'      => '系统管理',
                'component'  => 'Layout',
                'breadcrumb' => 0,
                'hidden'     => 0,
                'noCache'    => 0,
                'redirect'   => '/system/permission',
                'activeMenu' => '',
                'child' => [
                    [
                        'name'       => 'permission',
                        'path'       => 'permission',
                        'icon'       => '',
                        'title'      => '权限管理',
                        'component'  => 'system/index',
                        'breadcrumb' => 0,
                        'hidden'     => 0,
                        'noCache'    => 0,
                        'redirect'   => '',
                        'activeMenu' => '',
                        'child' => [
                            [
                                'name'       => 'permission.index',
                                'path'       => 'index',
                                'icon'       => '',
                                'title'      => '权限',
                                'component'  => 'system/permission/index',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/permission',
                            ],
                            [
                                'name'       => 'permission.create',
                                'path'       => 'create',
                                'icon'       => '',
                                'title'      => '添加权限',
                                'component'  => 'system/permission/create',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/permission',
                            ],
                            [
                                'name'       => 'permission.edit',
                                'path'       => 'edit',
                                'icon'       => '',
                                'title'      => '编辑权限',
                                'component'  => 'system/permission/edit',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/permission',
                            ],
                            [
                                'name'       => 'permission.destroy',
                                'path'       => 'destroy',
                                'icon'       => '',
                                'title'      => '删除权限',
                                'component'  => 'system/permission/destroy',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/permission',
                            ]
                        ]
                    ],
                    [
                        'name'       => 'role',
                        'path'       => 'role',
                        'icon'       => '',
                        'title'      => '角色管理',
                        'component'  => 'system/index',
                        'breadcrumb' => 0,
                        'hidden'     => 0,
                        'noCache'    => 0,
                        'redirect'   => '',
                        'activeMenu' => '',
                        'child' => [
                            [
                                'name'       => 'role.index',
                                'path'       => 'index',
                                'icon'       => '',
                                'title'      => '角色',
                                'component'  => 'system/role/index',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/role',
                            ],
                            [
                                'name'       => 'role.create',
                                'path'       => 'create',
                                'icon'       => '',
                                'title'      => '添加角色',
                                'component'  => 'system/role/create',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/role',
                            ],
                            [
                                'name'       => 'role.edit',
                                'path'       => 'edit',
                                'icon'       => '',
                                'title'      => '编辑角色',
                                'component'  => 'system/role/edit',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/role',
                            ],
                            [
                                'name'       => 'role.destroy',
                                'path'       => 'destroy',
                                'icon'       => '',
                                'title'      => '删除角色',
                                'component'  => 'system/role/destroy',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/role',
                            ],
                            [
                                'name'       => 'role.permission',
                                'path'       => 'permission',
                                'icon'       => '',
                                'title'      => '分配权限',
                                'component'  => 'system/role/permission',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/role',
                            ]
                        ]
                    ],
                    [
                        'name'       => 'manager',
                        'path'       => 'manager',
                        'icon'       => '',
                        'title'      => '管理员',
                        'component'  => 'system/index',
                        'breadcrumb' => 0,
                        'hidden'     => 0,
                        'noCache'    => 0,
                        'redirect'   => '',
                        'activeMenu' => '',
                        'child' => [
                            [
                                'name'       => 'manager.index',
                                'path'       => 'index',
                                'icon'       => '',
                                'title'      => '管理',
                                'component'  => 'system/manager/index',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/manager',
                            ],
                            [
                                'name'       => 'manager.create',
                                'path'       => 'create',
                                'icon'       => '',
                                'title'      => '添加管理',
                                'component'  => 'system/manager/create',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/manager',
                            ],
                            [
                                'name'       => 'manager.edit',
                                'path'       => 'edit',
                                'icon'       => '',
                                'title'      => '编辑管理',
                                'component'  => 'system/manager/edit',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/manager',
                            ],
                            [
                                'name'       => 'manager.destroy',
                                'path'       => 'destroy',
                                'icon'       => '',
                                'title'      => '删除管理',
                                'component'  => 'system/manager/destroy',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/manager',
                            ],
                            [
                                'name'       => 'manager.role',
                                'path'       => 'role',
                                'icon'       => '',
                                'title'      => '分配角色',
                                'component'  => 'system/manager/role',
                                'breadcrumb' => 0,
                                'hidden'     => 1,
                                'noCache'    => 0,
                                'redirect'   => '',
                                'activeMenu' => '/system/manager',
                            ]
                        ]
                    ],
                ]
            ]
        ];

        $role = Role::create([
            'name' => 'root',
            'title' => '站长'
        ]);

        foreach ($permissions as $pem1) {
            // 生成一级权限
            $p1 = Permission::create([
                'name' => $pem1['name'],
                'path' => $pem1['path'],
                'icon' => $pem1['icon']?:'',
                'title' => $pem1['title'],
                'component' => $pem1['component'],
                'breadcrumb' => $pem1['breadcrumb']?:1,
                'hidden' => $pem1['hidden']?:0,
                'noCache' => $pem1['noCache']?:0,
                'redirect' => $pem1['redirect']?:'',
                'activeMenu' => $pem1['activeMenu']?:'',
            ]);

            // 为角色添加权限
            $role->givePermissionTo($p1);
            // 为用户添加权限(用户不直接分配权限，需要通过角色来实现权限控制)
            // $user->givePermissionTo($p1);
            if (isset($pem1['child'])) {
                foreach ($pem1['child'] as $pem2) {
                    //生成二级权限
                    $p2 = Permission::create([
                        'name' => $pem2['name'],
                        'path' => $pem2['path'],
                        'icon' => $pem2['icon']?:'',
                        'title' => $pem2['title'],
                        'component' => $pem2['component'],
                        'breadcrumb' => $pem2['breadcrumb']?:1,
                        'hidden' => $pem2['hidden']?:0,
                        'noCache' => $pem2['noCache']?:0,
                        'redirect' => $pem2['redirect']?:'',
                        'activeMenu' => $pem2['activeMenu']?:'',
                        'parent_id' => $p1->id,
                    ]);
                    // 为角色添加权限
                    $role->givePermissionTo($p2);
                    // 为用户添加权限(用户不直接分配权限，需要通过角色来实现权限控制)
                    // $user->givePermissionTo($p2);
                    if (isset($pem2['child'])) {
                        foreach ($pem2['child'] as $pem3) {
                            //生成三级权限
                            $p3 = Permission::create([
                                'name' => $pem3['name'],
                                'path' => $pem3['path'],
                                'icon' => $pem3['icon']?:'',
                                'title' => $pem3['title'],
                                'component' => $pem3['component'],
                                'breadcrumb' => $pem3['breadcrumb']?:1,
                                'hidden' => $pem3['hidden']?:0,
                                'noCache' => $pem3['noCache']?:0,
                                'redirect' => $pem3['redirect']?:'',
                                'activeMenu' => $pem3['activeMenu']?:'',
                                'parent_id' => $p2->id,
                            ]);
                            // 为角色添加权限
                            $role->givePermissionTo($p3);
                            // 为用户添加权限(用户不直接分配权限，需要通过角色来实现权限控制)
                            // $user->givePermissionTo($p3);
                        }
                    }

                }
            }
        }

        // 如果用户不存在，才创建用户
        $user = User::where('account', 18208801176)->first();
        if (! $user) {
            $phone = 18208801176;
            $faker = \Faker\Factory::create('zh_CN');
            $user = User::create([
                'account' => $phone,
                'password' => Hash::make('123456'),
                'nickname' => 'TLITMK',
                'phone' => $phone,
                'gender' => 1,
                'avatar' => $faker->imageUrl,
                'email' => '761173360@qq.com',
                'signature' => '虚幻之物对应着冥冥之路。',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10)
            ]);
        }
        //为用户添加角色
        $user->assignRole($role);
    }
}