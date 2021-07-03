<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;

class CrmRule extends BaseModel
{
    protected $guarded = [];

    const ISLOG_ON = 1;  //记录日志
    const ISLOG_OFF = 0;  //不记录

    const  CHECK_NEED = 1;  //需要验证权限
    const  CHECK_NOTNEED = 0;   //不需要验证权限

    const TYPE_PERMISSION = 0;  //权限
    const TYPE_PERMISSION_OR_MENU = 1;    //权限和菜单

    public function roles()
    {
        return $this->belongsToMany(CrmRole::class, CrmRoleRule::class, 'rule_id', 'role_id');
    }

    public function getRules()
    {
        $rules = $this->orderBy('sort', 'asc')->get()->toArray();
        return $this->tree($rules);
    }

    public function tree($data, $pid = 0, $lvl = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                $lefthtml = '';
                if ($lvl == 1) $lefthtml = '├';
                if ($lvl == 2) $lefthtml = '├┈';
                $v['ltitle'] = $lefthtml . $v['title'];
                $arr[] = $v;
                unset($data[$k]);
                $arr = array_merge($arr, $this->tree($data, $v['id'], $lvl + 1));
            }
        }
        return $arr;
    }

    /**
     * 判断权限
     */
    public function permissionHidden($rulestr){
        $admin = Auth::guard('crm')->user();
        $rules = $admin->getAdminRules();
        if (!in_array($rulestr, $rules)){
            return false;
        }
        return true;
    }
}
