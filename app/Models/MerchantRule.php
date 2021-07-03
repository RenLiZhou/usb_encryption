<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MerchantRule extends BaseModel
{
    protected $guarded = [];
    public $appends = ['title_name'];

    const  CHECK_NEED = 1;  //需要验证权限
    const  CHECK_NOTNEED = 0;   //不需要验证权限

    public static function basicRules()
    {
        return [
            'title' => 'required',
            'pid' => 'required|numeric|min:0',
            'level' =>  'required|numeric',
        ];
    }

    public static function basicMessages()
    {
        return [
            'title.required' => '权限标识为空',
            'pid.required' => '上级权限错误',
            'pid.numeric' => '上级权限错误',
            'pid.min' => '上级权限错误',
            'level.required' => '层级异常',
            'level.numeric' => '层级异常'
        ];
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getTitleNameAttribute()
    {
        return __("merchant_menu.{$this->title}");
    }

    public function versions()
    {
        return $this->belongsToMany(MerchantVersion::class, MerchantVersionRule::class, 'rule_id', 'version_id');
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
                $v['ltitle'] = $lefthtml . $v['title_name'];
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
