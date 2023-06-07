<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function create(){
        return view('admin.add_view');
    }

    public function store(Request $request){
        dd($request->all());
    }

}
