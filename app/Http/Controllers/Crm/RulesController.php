<?php

namespace App\Http\Controllers\Crm;

use App\Models\CrmRule;
use App\Models\CrmAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RulesController extends Controller
{
    public $v = 'crm.rule.';

    public function index()
    {
        $rules = (new CrmRule())->getRules();
        return view($this->v . 'index', compact('rules'));
    }

    public function create()
    {
        $rules = (new CrmRule())->getRules();
        return view($this->v . 'create', compact('rules'));
    }

    public function store(Request $request)
    {
        $data = [
            'pid' => $request->pid??0,
            'title' =>  $request->title??'',
            'type' =>  $request->type??CrmRule::TYPE_PERMISSION,
            'href' =>  $request->href??'',
            'rule' =>  $request->rule??'',
            'icon' =>  $request->icon??'',
            'level' =>  $request->level??1,
            'check' => CrmRule::CHECK_NEED,
            'sort' => 50,
        ];

        $rule = CrmRule::create($data);
        if (!$rule){
            return responseError();
        }
        return responseSuccess();
    }

    public function edit(CrmRule $rule)
    {
        $rules = (new CrmRule())->getRules();
        $curRule = $rule;
        return view($this->v . 'edit', compact('rules', 'curRule'));
    }

    public function update(Request $request, CrmRule $rule)
    {
        $params = $request->all();
//        if (isset($params['pid']) && $params['pid'] != $rule->pid) {
//            $son_count = CrmRule::where('pid', $rule->id)->count();
//            if ($son_count > 0){
//                return responseError('该权限下面有子级，不能移动');
//            }
//        }

        $data = [
            'pid' => $params['pid']??$rule->pid,
            'title' =>  $params['title']??$rule->title,
            'type' =>  $params['type']??$rule->type,
            'href' =>  $params['href']??$rule->href,
            'rule' =>  $params['rule']??$rule->rule,
            'icon' =>  $params['icon']??$rule->icon,
            'level' =>  $params['level']??$rule->level,
            'islog' =>  $params['islog']??$rule->islog,
            'sort' =>  $params['sort']??$rule->sort,
            'check' =>  $params['check']??$rule->check
        ];

        $res = $rule->update($data);
        if (!$res){
            return responseError();
        }

        //清除缓存
        (new CrmAdmin())->cleanAdminData();
        return responseSuccess();
    }

    public function destroy(CrmRule $rule)
    {
        $has = CrmRule::where('pid', $rule->id)->count();
        if ($has > 0){
            return responseError('该权限下面有子级，不能删除');
        }
        $rule->roles()->detach();
        $res = $rule->delete();
        if (!$res) {
            return responseError();
        }

        (new CrmAdmin())->cleanAdminData();
        return responseSuccess();
    }
}
