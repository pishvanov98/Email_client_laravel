<?php

namespace App\Http\Controllers;

use App\Models\Email_queue;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function set(Request $request){
        $data_request=$request->all();
        if(!empty($data_request['data'])){
            $data=json_decode($data_request['data'],true);
                if($data['token'] == 'f2354a65cc810f0a73a0160a3e25628a' && !empty($data['email']) && !empty($data['data']) && !empty($data['pattern'])){
                        $email= new Email_queue();
                        $email->email=$data['email'];
                        $email->data=json_encode($data['data'],true);
                        $email->pattern=$data['pattern'];
                        $email->save();
                }
        }
    }
    public function test(){
        $data=[
          'token'=>md5('Avel'),
          'email'=>'nikita@aveldent.ru',
          'pattern'=>'product_subscription',
          'data'=>[
              'name'=>'Никита',
              'product'=>[
                  ['id'=>'111',
                  'name'=>'Наименование товара',
                  'img'=>'img.png',
                  'price'=>'1200',
                  'discount_price'=>'1000'],
                  [
                      'id'=>'122',
                      'name'=>'Наименование товара',
                      'img'=>'img.png',
                      'price'=>'1400',
                      'discount_price'=>'1000']
              ]
        ]];
       return redirect('/set?data='.json_encode($data,true));
    }
}
