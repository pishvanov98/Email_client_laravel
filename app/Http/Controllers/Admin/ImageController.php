<?php

namespace App\Http\Controllers\Admin;
use App\Models\Image;
use App\Models\View;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ImageController extends Controller
{
    public function index(){

        $all_img=Image::all();


        return view('admin.download_image',compact('all_img'));
    }

    public function store(Request $request){

        if($request->file('image')){
            $file= $request->file('image')->store('uploads','public_uploads');
            $file_name = $request->file('image')->getClientOriginalName();//получаю имя картинки

            $image= new Image();
            $image->image_to_server=$file;
            $image->image_select=$file_name;
            $image->save();
        }
      return  redirect()->route('admin.image.store');
    }

}
