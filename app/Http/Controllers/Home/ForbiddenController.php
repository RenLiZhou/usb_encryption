<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForbiddenController extends Controller
{
    public $v = 'home.forbidden.';

    public function index()
    {
        return view($this->v . 'index');
    }
}
