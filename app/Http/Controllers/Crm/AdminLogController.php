<?php

namespace App\Http\Controllers\Crm;

use App\Models\CrmAdminLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLogController extends Controller
{
    public $v = 'crm.admin_log.';

    /*
     * CRM网站日志
     */
    public function index(Request $request)
    {
        $admin_id = Auth::guard('crm')->id();
        $search = $request->input('search');
        $datas = CrmAdminLog::query()
            ->when(!empty($search),function ($query) use ($search){
                $query->where('ip', 'like', "%{$search}%")->orWhere('url', 'like', "%{$search}%");
            })
            ->where('admin_id', $admin_id)
            ->orderBy('id', 'desc')->paginate(15);

        return view($this->v . 'index', compact('search', 'datas'));
    }
}
