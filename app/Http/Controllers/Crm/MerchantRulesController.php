<?php

namespace App\Http\Controllers\Crm;

use App\Models\MerchantRule;
use App\Models\CrmAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class MerchantRulesController extends Controller
{
    public $v = 'crm.merchant_rule.';

    public function index()
    {
        $rules = (new MerchantRule())->getRules();
        return view($this->v . 'index', compact('rules'));
    }

    public function create()
    {
        $rules = (new MerchantRule())->getRules();
        return view($this->v . 'create', compact('rules'));
    }

    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, MerchantRule::basicRules(), MerchantRule::basicMessages())) {
            return responseError($message);
        }

        $data = [
            'pid' => $request->pid??0,
            'title' =>  $request->title??'',
            'href' =>  $request->href??'',
            'icon' =>  $request->icon??'',
            'level' =>  $request->level??1,
            'check' => $request->check??MerchantRule::CHECK_NEED,
            'sort' => 50,
        ];

        $rule = MerchantRule::create($data);
        if (!$rule) return responseError();
        return responseSuccess();
    }

    public function edit(MerchantRule $rule)
    {
        $rules = (new MerchantRule())->getRules();
        $curRule = $rule;
        return view($this->v . 'edit', compact('rules', 'curRule'));
    }

    public function update(Request $request, MerchantRule $rule)
    {
        $params = $request->all();
//        if (isset($params['pid']) && $params['pid'] != $rule->pid) {
//            $son_count = MerchantRule::where('pid', $rule->id)->count();
//            if ($son_count > 0){
//                return responseError('该权限下面有子级，不能移动');
//            }
//        }

        $data = [
            'pid' => $params['pid']??$rule->pid,
            'title' =>  $params['title']??$rule->title,
            'href' =>  $params['href']??$rule->href,
            'icon' =>  $params['icon']??$rule->icon,
            'level' =>  $params['level']??$rule->level,
            'sort' =>  $params['sort']??$rule->sort,
            'check' =>  $params['check']??$rule->check
        ];

        $res = $rule->update($data);
        if (!$res) return responseError();
        return responseSuccess();
    }

    public function destroy(MerchantRule $rule)
    {
        $has = MerchantRule::where('pid', $rule->id)->count();
        if ($has > 0){
            return responseError('该权限下面有子级，不能删除');
        }
        $rule->versions()->detach();
        $res = $rule->delete();
        if (!$res) {
            return responseError();
        }
        return responseSuccess();
    }
}
