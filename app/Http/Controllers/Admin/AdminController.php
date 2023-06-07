<?php

namespace App\Http\Controllers\Admin;
use App\Models\View;
use Illuminate\Support\Facades\Validator;
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

        $validator = Validator::make($request->all(), [
            'exampleInputNameView' => 'required|max:191',
            'exampleInputNameStatus' => 'required',
            'exampleInputNameContent' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/view/create')
                ->withErrors($validator)
                ->withInput();
        }

        $data=$request->all();

        $table= new View();
        $table->name=$data['exampleInputNameView'];
        $table->data=$data['exampleInputNameContent'];
        $table->status=$data['exampleInputNameStatus'];
        $table->save();

        return redirect('admin/');

    }

}
