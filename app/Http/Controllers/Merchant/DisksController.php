<?php

namespace App\Http\Controllers\Merchant;

use App\Exports\DiskTrackExport;
use App\Models\Disk;
use App\Models\StrategyAuth;
use App\Models\StrategyUpdate;
use App\Service\DiskService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\DiskTrack;
use Maatwebsite\Excel\Facades\Excel;

class DisksController extends Controller
{
    public $v = 'merchant.disks.';

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $merchant_id = Auth::guard('merchant')->id();

        $per_page = $request->input('per_page',10);
        $name = $request->input('name','');
        $usb_serial = $request->input('usb_serial','');

        $datas = Disk::query()
            ->with(['strategy_auth','strategy_update'])
            ->when(!empty($name),function ($query) use ($name) {
                $query->where('name', $name);
            })
            ->when(!empty($usb_serial),function ($query) use ($usb_serial) {
                $query->where('usb_serial', $usb_serial);
            })
            ->where('merchant_id', $merchant_id)
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        $disk_encryption_count = config('services.disk_encryption_count');

        $search_data = [
            'per_page' => $per_page,
            'name' => $name,
            'usb_serial' => $usb_serial
        ];
        return view($this->v . 'index', compact('datas', 'search_data', 'disk_encryption_count'));
    }

    /**
     * 编辑页面
     */
    public function edit($disk_id)
    {
        $merchant_id = Auth::guard('merchant')->id();
        $data = Disk::query()
            ->with(['strategy_auth','strategy_update'])
            ->where('merchant_id', $merchant_id)
            ->findOrFail($disk_id);

        $strategy_auth = StrategyAuth::query()->where('merchant_id', $merchant_id)->get();
        $strategy_update = StrategyUpdate::query()->where('merchant_id', $merchant_id)->get();

        return view($this->v . 'edit', compact('data', 'strategy_auth', 'strategy_update'));
    }

    /**
     * 更新数据
     */
    public function update(Request $request, $disk_id)
    {
        $validator = Validator::make($request->all(), [
            'update_id' => 'nullable|integer|min:0',
            'auth_id' => 'nullable|integer|min:0',
            'status' => ['required',Rule::in([Disk::STATUS_USE, Disk::STATUS_DISABLED]),]
        ],[
            'status.required' => '状态异常',
            'status.in' => '状态异常',
            'update_id.integer' => '文件更新策略错误',
            'update_id.min' => '文件更新策略错误',
            'auth_id.integer' => '权限策略错误',
            'auth_id.min' => '权限策略错误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $res = DiskService::updateDisk($disk_id, $request->all());
        if (!$res['result']){
            return responseError($res['msg']);
        }
        return responseSuccess();
    }

    /**
     * 启用禁用
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateStatus(Request $request, $disk_id)
    {
        $merchant_id = Auth::guard('merchant')->id();
        $disk = Disk::query()->where('merchant_id', $merchant_id)->findOrFail($disk_id);

        $disk->status = 1-$disk->status;
        if ($disk->save()){
            return responseSuccess();
        }
        return responseError();
    }

    /**
     * 批量启用禁用
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bacthUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required',Rule::in([Disk::STATUS_USE, Disk::STATUS_DISABLED]),],
            'ids' => 'required'
        ],[
            'type.required' => '操作异常',
            'type.in' => '操作异常',
            'ids.required' => '参数错误'
        ]);
        $error = $validator->errors()->first();
        if ($error) return responseError($error);

        $merchant_id = Auth::guard('merchant')->id();
        $status = $request->type;
        $ids = explode('|', $request->ids);

        $is_update = Disk::query()
            ->where('merchant_id', $merchant_id)
            ->whereIn('id', $ids)
            ->update(['status' => $status]);

        if ($is_update){
            return responseSuccess();
        }
        return responseError();
    }

    /**
     * 轨迹记录
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function track(Request $request, $disk_id){

        $merchant_id = Auth::guard('merchant')->id();
        $per_page = $request->input('per_page',10);

        $datas = DiskTrack::query()
            ->where('merchant_id', $merchant_id)
            ->where('disk_id', $disk_id)
            ->orderBy('id', 'desc')
            ->paginate($per_page);

        $search_data = [
            'per_page' => $per_page
        ];
        return view($this->v . 'track', compact('datas', 'search_data', 'disk_id'));
    }

    /**
     * 清空轨迹
     * @param $disk_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function emptyTrack($disk_id){

        $merchant_id = Auth::guard('merchant')->id();

        DiskTrack::query()
            ->where('merchant_id', $merchant_id)
            ->where('disk_id', $disk_id)
            ->delete();

        return responseSuccess();
    }

    /**
     * 导出轨迹
     * @param $disk_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportTrack($disk_id){

        $merchant_id = Auth::guard('merchant')->id();

        $datas = DiskTrack::query()
            ->where('merchant_id', $merchant_id)
            ->where('disk_id', $disk_id)
            ->orderBy('id', 'desc')
            ->limit(10000)
            ->get();

        $export = new DiskTrackExport($datas);
        $name = 'disk_track_'.$merchant_id.'_'.$disk_id.'_'.date('YmdHis').'.xlsx';
        return Excel::download($export, $name);
    }
}
