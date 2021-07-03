<?php

namespace App\Http\Controllers\Crm;

use App\Models\CrmRole;
use App\Models\CrmAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminsController extends Controller
{
    public $v = 'crm.admins.';

    /*
     * 列表
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $admins = CrmAdmin::where(function ($query) use ($search) {
            if ($search !== null || $search !== '') {
                $query->where('username', 'like', "{$search}%");
            }
        })->with('roles')->orderBy('id', 'desc')->paginate(12);
        return view($this->v . 'index', compact('search', 'admins'));
    }

    /*
     * 创建页面
     */
    public function create()
    {
        $roles = CrmRole::get();
        return view($this->v . 'create', compact('roles'));
    }


    /*
     * 保存数据
     */
    public function store(Request $request, CrmAdmin $admin)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'role_id' => 'required',
            'username' => 'required|alpha_dash|max:64|unique:crm_admins',
            'password' => 'required|confirmed|alpha_dash|min:6',
            'email' => 'email|nullable'
        ],[
            'role_id.required' => '用户组不能为空'
        ]);
        $error = $validator->errors()->first();
        if ($error) {
            return responseError($error);
        }

        $res = $admin->createAdmin($params);
        if (!$res){
            return responseError();
        }
        return responseSuccess();
    }


    /*
     * 删除
     */
    public function destroy(CrmAdmin $admin)
    {
        $admin->roles()->detach();
        $admin->delete();
        return responseSuccess();
    }


    /*
     * 编辑页面
     */
    public function edit($admin_id)
    {
        $roles = CrmRole::get();
        $admin = CrmAdmin::with('roles')->find($admin_id);
        return view($this->v . 'edit', compact('roles', 'admin'));
    }

    /*
     * 更新页面
     */
    public function update(Request $request, CrmAdmin $admin)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'role_id' => 'required',
            'username' => 'required|alpha_dash|max:64',
            'email' => 'email|nullable'
        ],[
            'role_id.required' => '用户组不能为空'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);
        $res = $admin->updateAdmin($params, $admin);
        if (!$res) return responseError();
        return responseSuccess();
    }

    /*
     * 修改密码页面
     */
    public function editPassword(CrmAdmin $admin)
    {
        return view($this->v . 'editPwd', compact('admin'));
    }

    /*
     * 更新密码
     */
    public function updatePassword(Request $request, CrmAdmin $admin)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|alpha_dash|min:6',
        ]);
        $error = $validator->errors()->first();
        if ($error){
            return responseError($error);
        }

        $res = $admin->updatePwd($request->password, $admin);
        if (false === $res){
            return responseError();
        }
        return responseSuccess();
    }

    /*
     * 更新状态
     */
    public function updateStatus(Request $request, CrmAdmin $admin)
    {
        $admin->status = 1-$admin->status;
        if ($admin->save()){
            return responseSuccess();
        }
        return responseError();
    }
}
