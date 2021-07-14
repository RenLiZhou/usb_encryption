<?php

namespace App\Http\Controllers\Crm;

use App\Models\Language;
use App\Models\Merchant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public $v = 'crm.language.';

    /**
     * 列表
     */
    public function index()
    {
        $datas = Language::get();
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
        if ($message = $this->validateParams($params, Language::basicRules(), Language::basicMessages())) {
            return responseError($message);
        }

        $res = Language::createData($params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    /**
     * 编辑页面
     */
    public function edit($id)
    {
        $data = Language::query()->findOrFail($id);
        return view($this->v . 'edit', compact('data'));
    }

    /**
     * 更新数据
     */
    public function update(Request $request, Language $language)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, Language::basicRules(), Language::basicMessages())) {
            return responseError($message);
        }

        $res = Language::updateData($language->id, $params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    /**
     * 删除数据
     */
    public function destroy(Language $language)
    {
        $exists = Merchant::query()->where('lang_id',$language->id)->exists();
        if($exists){
            return responseError('该语言已生效,不允许删除');
        }
        $language->delete();
        return responseSuccess();
    }

}
