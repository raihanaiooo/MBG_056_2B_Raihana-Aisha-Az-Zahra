<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpParser\Builder\Function_;

class GudangController extends Controller
{
    public function index(){
        return view('gudang.index');
    }
}
