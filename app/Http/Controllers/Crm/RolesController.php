<?php

namespace App\Http\Controllers\Crm;

use App\Models\CrmRole;
use App\Models\CrmAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    public $v = 'crm.role.';

    public function index()
    {
        $roles = CrmRole::get();
        return view($this->v . 'index', compact('roles'));
    }

    public function store(Request $request)
    {
        $name = $request->input('name');
        $has = CrmRole::where('name', $name)->count();
        if ($has > 0) {
            return responseError('该角色名已存在');
        }

        $role = CrmRole::create(['name' => $name]);
        if (!$role) {
            return responseError();
        }
        return responseSuccess();
    }

    public function update(Request $request, CrmRole $role)
    {
        $name = $request->input('name');
        if ($name != $role->name) {
            $has = $role->where('name', $name)->count();
            if ($has > 0){
                return responseError('该角色名已存在');
            }
            $role->update(['name' => $name]);
        }
        return responseSuccess();
    }

    public function destroy(Request $request, CrmRole $role)
    {
        $role->admins()->detach();
        $role->rules()->detach();
        $role->delete();
        return responseSuccess();
    }

    public function setRules(Request $request, CrmRole $role)
    {
        $rules = $role->ztreeRules();
        return view($this->v . 'set', ['role' => $role, 'rules' => json_encode($rules)]);
    }

    public function settedRules(Request $request, CrmRole $role)
    {
        $rules = $request->input('rules');
        $res = $role->rules()->sync($rules);
        if (!$res){
            return responseError();
        }

        (new CrmAdmin())->cleanAdminData();
        return responseSuccess();
    }
}
