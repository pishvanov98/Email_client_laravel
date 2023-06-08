<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmailSend;
use App\Models\Email_queue;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function getValEmail($request){
        $data_request=$request->all();
        $email_mass=[];
        if(!empty($data_request['data'])){
            $data=json_decode($data_request['data'],true);
                if($data['token'] == 'f2354a65cc810f0a73a0160a3e25628a' && !empty($data['email']) && !empty($data['data']) && !empty($data['pattern'])){
                    $email_mass['email']=$data['email'];
                    $email_mass['data']=$data['data'];
                    $email_mass['pattern']=$data['pattern'];
                }
        }
        return $email_mass;
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
       return redirect('/send?data='.json_encode($data,true));
    }

    public function sendEmail(Request $request){
       $data= $this->getValEmail($request);
var_dump($data);
        exit();
        $name= 'Никсон';//переменная из очереди писем таблицы
        $view= View::all()->where('name','=','default')->toArray();//берем данные шаблона и ищем в нем переменные разделенные | и заменяем на настоящие переменные и передаем в шаблон
        $content = str_replace("|Name|",$name, $view[0]['data']);

        $this->dispatch(new ProcessEmailSend($content));

    }



}
