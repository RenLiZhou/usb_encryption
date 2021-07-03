<?php

namespace App\Http\Controllers\Merchant;

use App\Models\MerchantVersionRelation;
use App\Service\MerchantService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public $v = 'merchant.index.';

    /**
     * 首页
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $merchant = Auth::guard('merchant')->user();
        $menus = $merchant->getMerchantRules();
        $menus = MerchantService::treeMenu($menus);
        return view($this->v . 'index', compact('merchant', 'menus'));
    }

    /**
     * 概括
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overview(){
        $merchant = Auth::guard('merchant')->user()
            ->with(['version','language'])
            ->first();
        return view($this->v . 'overview', compact('merchant'));
    }
}
