<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class RulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rules')->insert([
            ['title' => '首页', 'href' => '/admin/first', 'rule' => 'admin.first', 'pid' => 0, 'check' => 0, 'type' => 1, 'level' => 1, 'icon' => 'layui-icon-home', 'sort' => 1, 'islog' => 0],
            ['title' => '修改密码', 'href' => null, 'rule' => 'admin.safe', 'pid' => 1, 'check' => 1, 'type' => 0, 'level' => 2, 'icon' => '', 'sort' => 50, 'islog' => 1],
            ['title' => '权限管理', 'href' => null, 'rule' => null, 'pid' => 0, 'check' => 1, 'type' => 1, 'level' => 1, 'icon' => 'layui-icon-vercode', 'sort' => 50, 'islog' => 0],
            ['title' => '管理员', 'href' => '/admin/admin', 'rule' => 'admin.index', 'pid' => 3, 'check' => 1, 'type' => 1, 'level' => 2, 'icon' => null, 'sort' => 50, 'islog' => 0],
            ['title' => '添加管理员页面', 'href' => null, 'rule' => 'admin.create', 'pid' => 4, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 0],
            ['title' => '添加管理员', 'href' => null, 'rule' => 'admin.store', 'pid' => 4, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '禁用管理员', 'href' => null, 'rule' => 'admin.active', 'pid' => 4, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '编辑管理员页面', 'href' => null, 'rule' => 'admin.edit', 'pid' => 4, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '编辑管理员', 'href' => null, 'rule' => 'admin.update', 'pid' => 4, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '权限列表', 'href' => '/admin/rule', 'rule' => 'rule.index', 'pid' => 3, 'check' => 1, 'type' => 1, 'level' => 2, 'icon' => null, 'sort' => 50, 'islog' => 0],
            ['title' => '添加权限页面', 'href' => null, 'rule' => 'rule.create', 'pid' => 10, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '添加权限', 'href' => null, 'rule' => 'rule.store', 'pid' => 10, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '编辑权限页面', 'href' => null, 'rule' => 'rule.edit', 'pid' => 10, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '编辑权限', 'href' => null, 'rule' => 'rule.update', 'pid' => 10, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '删除权限', 'href' => null, 'rule' => 'rule.destroy', 'pid' => 10, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '角色列表', 'href' => '/admin/role', 'rule' => 'role.index', 'pid' => 3, 'check' => 1, 'type' => 1, 'level' => 2, 'icon' => null, 'sort' => 50, 'islog' => 0],
            ['title' => '添加角色', 'href' => null, 'rule' => 'role.store', 'pid' => 16, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '编辑角色', 'href' => null, 'rule' => 'role.update', 'pid' => 16, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '删除角色', 'href' => null, 'rule' => 'role.destroy', 'pid' => 16, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '配置权限页面', 'href' => null, 'rule' => 'role.set', 'pid' => 16, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '配置权限', 'href' => null, 'rule' => 'role.setted', 'pid' => 16, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '删除管理员', 'href' => null, 'rule' => 'admin.destroy', 'pid' => 4, 'check' => 1, 'type' => 0, 'level' => 3, 'icon' => null, 'sort' => 50, 'islog' => 1],
            ['title' => '系统管理', 'href' => null, 'rule' => null, 'pid' => 0, 'check' => 1, 'type' => 1, 'level' => 1, 'icon' => 'layui-icon-set', 'sort' => 50, 'islog' => 0],
            ['title' => '管理员日志', 'href' => '/admin/system/adminlog', 'rule' => 'admin.adminlog', 'pid' => 23, 'check' => 1, 'type' => 1, 'level' => 2, 'icon' => null, 'sort' => 50, 'islog' => 0],
        ]);

        $now = date('Y-m-d H:i:s');
        $roleId = DB::table('roles')->insertGetId([
            'name' => '超级管理员',
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $roleId = 1;
        $rules = DB::table('rules')->pluck('id');
        $arr = [];
        foreach ($rules as $row) {
            $arr[] = [
                'role_id' => $roleId,
                'rule_id' => $row,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
        DB::table('role_rule')->insert($arr);

        $adminId = DB::table('admins')->value('id');
        DB::table('admin_role')->insert([
            'admin_id' => $adminId,
            'role_id' => $roleId,
            'created_at' => $now,
            'updated_at' => $now
        ]);

    }
}
