<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForbiddenController extends Controller
{
    public $v = 'web.forbidden.';

    public function index()
    {
        return view($this->v . 'index');
    }
}
