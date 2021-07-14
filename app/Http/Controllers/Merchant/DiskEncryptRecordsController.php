<?php

namespace App\Http\Controllers\Merchant;

use App\Models\DiskEncryptRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class DiskEncryptRecordsController extends Controller
{
    public $v = 'merchant.disk_encrypt_records.';

    //加密记录
    public function index(Request $request){
        $merchant_id = Auth::guard('merchant')->id();

        $per_page = $request->input('per_page',10);

        $datas = DiskEncryptRecord::query()
            ->with('disk')
            ->where('merchant_id', $merchant_id)
            ->orderBy('id', 'desc')
            ->paginate($per_page);
        $search_data = [
            'per_page' => $per_page
        ];

        return view($this->v . 'index', compact('datas', 'search_data'));
    }
}
