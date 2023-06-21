<?php

namespace App\Http\Controllers\Admin;
use App\Models\Email_queue;
use App\Models\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class RequestController extends Controller
{
    public function index(){


        $email_request= Email_queue::orderBy('id', 'DESC')->paginate(15);

       return view('admin.request',compact('email_request'));
    }


}
