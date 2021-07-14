<?php

namespace App\Http\Controllers\Crm;

use App\Models\Language;
use App\Models\Merchant;
use App\Models\MerchantVersion;
use App\Service\ResourceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MerchantsController extends Controller
{
    public $v = 'crm.merchant.';

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $search = $request->input('search','');
        $version = $request->input('version',0);

        $merchant_version = MerchantVersion::query()->get();

        $datas = Merchant::query()
            ->when(!empty($search),function ($query) use ($search) {
                $query->where('name', 'like', "{$search}%");
            })
            ->when(!empty($version) && $version > 0,function ($query) use ($version) {
                $query->join('merchant_version_relation', function ($join) use ($version){
                    $join->on('merchants.id', 'merchant_version_relation.merchant_id')
                        ->where('merchant_version_relation.version_id', $version);
                });
            })
            ->select('merchants.*')
            ->with(['version','language'])
            ->orderBy('id', 'desc')->paginate(12);

        $search_data = [
            'search' => $search,
            'version' => $version
        ];
        return view($this->v . 'index', compact('search_data', 'datas', 'merchant_version'));
    }

    /**
     * 创建商户页面
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $versions = MerchantVersion::get();
        $languages = Language::get();
        return view($this->v . 'create', compact('versions', 'languages'));
    }

    /**
     * 保存商户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, Merchant::createRules(), Merchant::basicMessages())) {
            return responseError($message);
        }

        $res = Merchant::createData($params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    /**
     * 编辑商户页面
     * @param $uid
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($uid)
    {
        $versions = MerchantVersion::get();
        $languages = Language::get();
        $merchant = Merchant::with(['version'])->find($uid);
        return view($this->v . 'edit', compact('versions', 'merchant', 'languages'));
    }

    /**
     * 更新商户
     * @param Request $request
     * @param $merchant_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Merchant $merchant)
    {
        $params = $request->all();
        if ($message = $this->validateParams($params, Merchant::updateRules(), Merchant::basicMessages())) {
            return responseError($message);
        }

        $res = Merchant::updateData($merchant->id, $params);
        if (!$res['result']) return responseError($res['msg']);
        return responseSuccess();
    }

    public function destroy(Merchant $merchant)
    {
//        $merchant->version()->detach();
        $merchant->username = $merchant->username.'_delete_'.date('YmdHis');
        $merchant->save();
        $merchant->delete();

//        $resource = new ResourceService();
//        $resource->deleteDirectory($merchant->root_directory);

        return responseSuccess();
    }

    public function updateStatus(Request $request, Merchant $merchant)
    {
        $merchant->status = 1-$merchant->status;
        if ($merchant->save()){
            return responseSuccess();
        }
        return responseError();
    }
}
