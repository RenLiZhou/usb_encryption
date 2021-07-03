<?php

namespace App\Http\Controllers\Merchant;

use App\Models\StrategyUpdate;
use App\Models\StrategyUpdateFiles;
use App\Service\StrategyUpdateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StrategyUpdateController extends Controller
{
    public $v = 'merchant.strategy_update.';

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $merchant_id = Auth::guard('merchant')->id();

        $per_page = $request->input('per_page',10);
        $name = $request->input('name','');

        $datas = StrategyUpdate::query()
            ->withCount('files')
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
            'hint' => 'nullable|integer|min:0',
            'valid_type' => 'required|integer|min:1',
            'valid_time' => 'nullable|date'
        ],[
            'name.required' => '策略名称为空',
            'hint.integer' => '非法参数',
            'hint.min' => '非法参数',
            'valid_type.required' => '生效时间有误',
            'valid_type.integer' => '生效时间有误',
            'valid_type.min' => '生效时间有误',
            'valid_time.date' => '生效时间有误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $res = StrategyUpdateService::createStrategy($request->all());
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
        $data = StrategyUpdate::query()
            ->with('files')
            ->where('merchant_id', $merchant_id)
            ->findOrFail($strategy_id);

        $strategy_files = json_encode($data->files);

        return view($this->v . 'edit', compact('data','strategy_files'));
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
            'hint' => 'nullable|integer|min:0',
            'valid_type' => 'required|integer|min:1',
            'valid_time' => 'nullable|date'
        ],[
            'name.required' => '策略名称为空',
            'hint.integer' => '非法参数',
            'hint.min' => '非法参数',
            'valid_type.required' => '生效时间有误',
            'valid_type.integer' => '生效时间有误',
            'valid_type.min' => '生效时间有误',
            'valid_time.date' => '生效时间有误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $res = StrategyUpdateService::updateStrategy($strategy_id, $request->all());
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
        $strategy_update = StrategyUpdate::query()->where('merchant_id', $merchant_id)->findOrFail($strategy_id);
        $strategy_update->delete();

        StrategyUpdateFiles::query()->where('strategy_id',$strategy_id)->delete();

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

        $ids = StrategyUpdate::query()
            ->where('merchant_id', $merchant_id)
            ->whereIn('id', $ids)
            ->pluck('id')->toArray();

        if(!empty($ids)){
            StrategyUpdate::query()->whereIn('id', $ids)->delete();

            StrategyUpdateFiles::query()->whereIn('strategy_id', $ids)->delete();
        }

        return responseSuccess();
    }
}
