<?php

namespace App\Http\Controllers\Merchant;

use App\Models\StrategyUpdate;
use App\Models\StrategyUpdateFiles;
use App\Service\ResourceService;
use App\Service\StrategyUpdateService;
use Carbon\Carbon;
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
     * 保存
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
            'name.required' => __('merchant_controller.update_strategy_name_is_empty'),
            'hint.integer' => __('common.parameter_error'),
            'hint.min' => __('common.parameter_error'),
            'valid_type.required' => __('merchant_controller.the_effective_time_is_wrong'),
            'valid_type.integer' => __('merchant_controller.the_effective_time_is_wrong'),
            'valid_type.min' => __('merchant_controller.the_effective_time_is_wrong'),
            'valid_time.date' => __('merchant_controller.the_effective_time_is_wrong')
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
     * 编辑
     * @param $uid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $strategy_id)
    {
        $merchant = Auth::guard('merchant')->user();
        $merchant_id = $merchant->id;

        $data = StrategyUpdate::query()
            ->with('files')
            ->where('merchant_id', $merchant_id)
            ->findOrFail($strategy_id);

        $ResourceService = new ResourceService();
        $strategy_files = $data->files;
        $files = [];
        foreach($strategy_files as $key => $file){
            $path = $merchant->root_directory.$file->path;
            $exists = $ResourceService->exists($path);
            if($exists){
                $files[] = [
                    'path' => $file->path,
                    'name' => $ResourceService->basename($path),
                    'size' => $ResourceService->size($path)
                ];
            }
        }

        $strategy_files = json_encode($files);

        return view($this->v . 'edit', compact('data','strategy_files'));
    }

    /**
     * 更新
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
            'name.required' => __('merchant_controller.update_strategy_name_is_empty'),
            'hint.integer' => __('common.parameter_error'),
            'hint.min' => __('common.parameter_error'),
            'valid_type.required' => __('merchant_controller.the_effective_time_is_wrong'),
            'valid_type.integer' => __('merchant_controller.the_effective_time_is_wrong'),
            'valid_type.min' => __('merchant_controller.the_effective_time_is_wrong'),
            'valid_time.date' => __('merchant_controller.the_effective_time_is_wrong')
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
