<?php

namespace App\Http\Controllers;

use App\Models\Email_queue;
use App\Models\View;
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
          'pattern'=>'default',
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
       $emails_mass= Email_queue::all()->where('status', '=', '0')->toArray();


       foreach ($emails_mass as $email_mass){
           $email='';
           $pattern='';
           $name='';
           $product=[];
           $email=$email_mass['email'];
           $pattern=$email_mass['pattern'];
           $email_mass['data']=json_decode($email_mass['data'],true);
           foreach ($email_mass['data'] as $key=>$data){
               if($key == 'name'){
                   $name=$data;
               }else{
                   $product[]=$data;
               }
           }
           //тут отправляем почту подставив переменные в шаблон
       }
exit();

        $name= 'Никсон';//переменная из очереди писем таблицы
        $view= View::all()->where('name','=','default')->toArray();//берем данные шаблона и ищем в нем переменные разделенные | и заменяем на настоящие переменные и передаем в шаблон
        $content = str_replace("|Name|",$name, $view[0]['data']);
      Mail::send('default',['content'=>$content],function ($message){
          $message->to('nikita@aveldent.ru','to dev block')->subject('test mail');//кому
          $message->from('nikita@aveldent.ru','to dev block22')->subject('test mail');//от
      });
    }

}
