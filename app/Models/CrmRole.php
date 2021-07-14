<?php

namespace App\Models;

class CrmRole extends BaseModel
{
    protected $guarded = [];

    public function admins()
    {
        return $this->belongsToMany(CrmAdmin::class, CrmAdminRole::class, 'role_id', 'admin_id');
    }

    public function rules()
    {
        return $this->belongsToMany(CrmRule::class, CrmRoleRule::class, 'role_id', 'rule_id');
    }

    public function ztreeRules()
    {
        $curRules = $this->rules()->pluck('crm_rules.id')->toArray();
        $allRules = CrmRule::select('id', 'title', 'pid')->orderBy('sort', 'asc')->get()->toArray();
        $rules = $this->buildZtreeData($allRules, $curRules);
        $rules[] = [
            "id"=>0,
            "pid"=>0,
            "title"=>"全部",
            "open"=>true
        ];
        return $rules;
    }

    public function buildZtreeData(array $data, array $checked, $pid = 0)
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v['pid'] == $pid) {
                if (in_array($v['id'], $checked)) {
                    $v['checked'] = true;
                }
                $v['open'] = true;
                $arr[] = $v;
                unset($data[$k]);
                $arr = array_merge($arr, $this->buildZtreeData($data, $checked, $v['id']));
            }
        }
        return $arr;
    }
}
