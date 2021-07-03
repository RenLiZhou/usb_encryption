<?php

namespace App\Http\Controllers\Merchant;

use App\Models\StrategyAuth;
use App\Service\StrategyAuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StrategyAuthController extends Controller
{
    public $v = 'merchant.strategy_auth.';

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $merchant_id = Auth::guard('merchant')->id();

        $per_page = $request->input('per_page',10);
        $name = $request->input('name','');

        $datas = StrategyAuth::query()
            ->when(!empty($name),function ($query) use ($name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->where('merchant_id', $merchant_id)
            ->orderBy('id', 'desc')
            ->paginate($per_page);
        $search_data = [
            'per_page' => $per_page,
            'name' => $name
        ];
        return view($this->v . 'index', compact('datas', 'search_data'));
    }

    /**
     * 创建商户页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view($this->v . 'create');
    }

    /**
     * 保存商户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'run_number' => 'required|integer|min:-1',
            'expired_type' => 'required|integer|min:0|max:2',
            'expired_time' => 'nullable|date',
            'expired_day' => 'nullable|integer|min:0',
        ],[
            'name.required' => '策略名称为空',
            'run_number.required' => '运行次数有误',
            'run_number.integer' => '运行次数有误',
            'run_number.min' => '运行次数有误',
            'expired_type.required' => '非法参数',
            'expired_type.integer' => '非法参数',
            'expired_type.min' => '非法参数',
            'expired_type.max' => '非法参数',
            'expired_day.integer' => '过期天数有误',
            'expired_day.min' => '过期天数有误',
            'expired_time.date' => '过期日期有误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $res = StrategyAuthService::createStrategy($request->all());
        if (!$res['result']){
            return responseError($res['msg']);
        }
        return responseSuccess();
    }

    /**
     * 编辑商户页面
     * @param $uid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $strategy_id)
    {
        $merchant_id = Auth::guard('merchant')->id();
        $data = StrategyAuth::query()
            ->where('merchant_id', $merchant_id)
            ->findOrFail($strategy_id);

        return view($this->v . 'edit', compact('data'));
    }

    /**
     * 更新商户
     * @param Request $request
     * @param $merchant_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $strategy_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'run_number' => 'required|integer|min:0',
            'expired_type' => 'required|integer|min:-1',
            'expired_time' => 'nullable|date',
            'expired_day' => 'nullable|integer|min:0',
        ],[
            'name.required' => '策略名称为空',
            'run_number.required' => '运行次数有误',
            'run_number.integer' => '运行次数有误',
            'run_number.min' => '运行次数有误',
            'expired_type.required' => '非法参数',
            'expired_type.integer' => '非法参数',
            'expired_type.min' => '非法参数',
            'expired_day.integer' => '过期天数有误',
            'expired_day.min' => '过期天数有误',
            'expired_time.date' => '过期日期有误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $res = StrategyAuthService::updateStrategy($strategy_id, $request->all());
        if (!$res['result']){
            return responseError($res['msg']);
        }
        return responseSuccess();
    }

    /**
     * 删除
     * @param int $strategy_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(int $strategy_id)
    {
        $merchant_id = Auth::guard('merchant')->id();
        $strategy_update = StrategyAuth::query()->where('merchant_id', $merchant_id)->findOrFail($strategy_id);
        $strategy_update->delete();

        return responseSuccess();
    }

    /**
     * 批量删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bacthDestroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required'
        ],[
            'ids.required' => '参数错误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $merchant_id = Auth::guard('merchant')->id();
        $ids = explode('|', $request->ids);

        StrategyAuth::query()
            ->where('merchant_id', $merchant_id)
            ->whereIn('id', $ids)
            ->delete();

        return responseSuccess();
    }
}
