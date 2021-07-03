<?php

namespace App\Http\Controllers\Crm;

use App\Models\CrmAdmin;
use App\Service\SysService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public $v = 'crm.index.';

    public function index()
    {
        $admin = Auth::guard('crm')->user();
        $menu = $admin->getAdminMenu();
        return view($this->v . 'index', ['admin' => $admin, 'menu' => json_encode($menu)]);
    }

    public function flushCache()
    {
        $admin = Auth::guard('crm')->user();
        $admin->getAdminMenu(true);
        $admin->getAdminRules(true);
        (new SysService())->getSystemInfo(true);
        return responseSuccess();
    }

    public function cleanCache()
    {
        (new CrmAdmin())->cleanAdminData();
        (new SysService())->cleanSysInfo();
        return responseSuccess();
    }

    public function first()
    {
        $sysinfo = (new SysService())->getSystemInfo();
        return view($this->v . 'first', compact('sysinfo'));
    }

}
