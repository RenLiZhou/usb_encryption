<?php

namespace App\Http\Controllers\Crm;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public $v = 'crm.setting.';

    /**
     * 列表
     */
    public function email()
    {
        $setting = Setting::getSetting(Setting::EMAIL);
        if (!$setting['result']){
            abort(403,$setting['msg']);
        }
        return view($this->v . 'email', compact('setting'));
    }

    /**
     * 保存数据
     */
    public function updateEmail(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, Language::basicRules(), Language::basicMessages())) {
            return responseError($message);
        }

        $res = Language::createData($params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

}
