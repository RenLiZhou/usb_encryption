<?php

namespace App\Http\Controllers\Crm;

use App\Models\ActivationCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivationCodeController extends Controller
{
    public $v = 'crm.activation_code.';

    /**
     * 列表
     */
    public function index(Request $request)
    {
        $code = $request->input('code','');
        $batch_no = $request->input('batch_no','');
        $status = $request->input('status');

        $datas = ActivationCode::query()
            ->when(!empty($name),function ($query) use ($code) {
                $query->where('code', 'like', "{$code}%");
            })
            ->when(!empty($batch_no),function ($query) use ($batch_no) {
                $query->where('batch_no', $batch_no);
            })
            ->when(isset($status),function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(12);

        $search_data = [
            'code' => $code,
            'batch_no' => $batch_no,
            'status' => $status
        ];
        return view($this->v . 'index', compact('datas', 'search_data'));
    }

    /**
     * 创建页面
     */
    public function create()
    {
        return view($this->v . 'create');
    }

    /**
     * 保存数据
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, ActivationCode::basicRules(), ActivationCode::basicMessages())) {
            return responseError($message);
        }

        $res = ActivationCode::createData($params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    /**
     * 删除数据
     */
    public function destroy(ActivationCode $activation_code)
    {
        if($activation_code->status == ActivationCode::STATUS_ACTIVE){
            return responseError('该激活码已激活不允许删除');
        }
        $activation_code->delete();
        return responseSuccess();
    }

    /**
     * 批次号查询
     */
    public function batchNo($batch_no)
    {
        $datas = ActivationCode::query()->where('batch_no', $batch_no)->get();
        return view($this->v . 'batch_no', compact('datas'));
    }

}
