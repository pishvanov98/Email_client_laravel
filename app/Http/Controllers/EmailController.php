<?php

namespace App\Http\Controllers;

use App\Models\Email_queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
                  'img'=>'https://aveldent.ru/image/cache/258/import-files-be-be4bf215-0058-11ee-80f1-0cc47aab4f67-c4c098b2-0058-11ee-80f1-0cc47aab4f67.png-258x258.png',
                  'price'=>'1200',
                  'discount_price'=>'1000'],
                  [
                      'id'=>'122',
                      'name'=>'Наименование товара',
                      'img'=>'https://aveldent.ru/image/cache/258/import-files-be-be4bf215-0058-11ee-80f1-0cc47aab4f67-c4c098b2-0058-11ee-80f1-0cc47aab4f67.png-258x258.png',
                      'price'=>'1400',
                      'discount_price'=>'1000']
              ]
        ]];
       return redirect('/set?data='.json_encode($data,true));
    }

    public function sendEmail(){
     //  $email= Email_queue::all()->where('status', '=', '0');
      Mail::send(['text'=>'product_subscription'],['name'=>'web dev'],function ($message){
          $message->to('nikita@aveldent.ru','to dev block')->subject('test mail');
          $message->from('nikita@aveldent.ru','to dev block')->subject('test mail');
      });
    }
}
