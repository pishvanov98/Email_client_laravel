<?php

namespace App\Http\Controllers\Admin;
use App\Models\View;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){

        $view= View::all();

        return view('admin.index',compact('view'));
    }

    public function create(){
        return view('admin.add_view');
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'exampleInputNameView' => 'required',
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

    public function edit(Request $request){
        $id= $request->route('id');
        $view=View::findOrFail($id);

        return view('admin.update_view',compact('view'));
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'exampleInputNameView' => 'required',
            'exampleInputNameStatus' => 'required',
            'exampleInputNameContent' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('admin/view/'.$request->route('id').'/update')
                ->withErrors($validator)
                ->withInput();
        }

        $data=$request->all();

        $view= View::findOrFail($request->route('id'));
        $view->name=$data['exampleInputNameView'];
        $view->data=$data['exampleInputNameContent'];
        $view->status=$data['exampleInputNameStatus'];
        $view->update();
        return redirect('/admin');
    }

    public function destroy(Request $request){
        $view=View::findOrFail($request->route('id'));
        $view->delete();
        return redirect('/admin');
    }

}
