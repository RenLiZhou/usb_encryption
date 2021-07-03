<?php

namespace App\Http\Controllers\Crm;

use App\Models\MerchantVersion;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MerchantVersionController extends Controller
{
    public $v = 'crm.merchant_version.';

    /**
     * 列表
     */
    public function index()
    {
        $datas = MerchantVersion::get();
        return view($this->v . 'index', compact('datas'));
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
        if ($message = $this->validateParams($params, MerchantVersion::basicRules(), MerchantVersion::basicMessages())) {
            return responseError($message);
        }

        $res = MerchantVersion::createData($params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    /**
     * 编辑页面
     */
    public function edit($id)
    {
        $data = MerchantVersion::query()->findOrFail($id);
        return view($this->v . 'edit', compact('data'));
    }

    /**
     * 更新数据
     */
    public function update(Request $request, $version_id)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, MerchantVersion::basicRules(), MerchantVersion::basicMessages())) {
            return responseError($message);
        }

        $res = MerchantVersion::updateData($version_id, $params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    /**
     * 删除数据
     */
    public function destroy(MerchantVersion $version)
    {
        $version->merchants()->detach();
        $version->rules()->detach();
        $version->delete();
        return responseSuccess();
    }

    /**
     * 配置版本权限页面
     */
    public function setRules(MerchantVersion $version)
    {
        $rules = $version->ztreeRules();
        return view($this->v . 'set', ['version' => $version, 'rules' => json_encode($rules)]);
    }

    /**
     * 保存版本权限
     */
    public function settedRules(Request $request, MerchantVersion $version)
    {
        $rules = $request->input('rules');
        $res = $version->rules()->sync($rules);
        if (!$res){
            return responseError();
        }
        return responseSuccess();
    }
}
