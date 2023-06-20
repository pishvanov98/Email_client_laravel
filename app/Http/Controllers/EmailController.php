<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmailSend;
use App\Models\Email_disabled;
use App\Models\Email_queue;
use App\Models\Log;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\View\Compilers\BladeCompiler;

class EmailController extends Controller
{
    public $count=0;
    public function getValEmail($request){
        $data_request=$request->all();
        $email_mass=[];
        if(!empty($data_request['data'])){
            $data=json_decode($data_request['data'],true);
                if($data['token'] == env('TOKEN') && !empty($data['email']) && !empty($data['title']) && !empty($data['data']) && !empty($data['pattern'])){
                    $email_mass['email']=$data['email'];
                    $email_mass['title']=$data['title'];
                    $email_mass['data']=$data['data'];
                    $email_mass['pattern']=$data['pattern'];
                }
        }
        return $email_mass;
    }
    public function test(){

        $data=[
          'token'=>env('TOKEN'),
          'email'=>'nikita@aveldent.ru',
          'title'=>'Поступление товара',
          'pattern'=>'entrance',//поступление товара
          'data'=>[
              'name'=>'Никита',
              'add_value'=>'',
              'product'=>[
                  ['id'=>'15827',
                  'name'=>'Таблетки для очистки съемных ортодонтических конструкций PRESIDENT PROFI ORTHO шипучие (30 шт)',
                  'img'=>'https://aveldent.ru/image/cache/460/import-files-be-be4bf215-0058-11ee-80f1-0cc47aab4f67-c4c098b2-0058-11ee-80f1-0cc47aab4f67.png-460x460.png',
                  'price'=>'1200',
                  'href'=>'https://aveldent.ru/apteka/uhod-za-polostu-rta/tabletki-dlya-ochistki-semnih-ortodonticheskih-konstruktsij-president-profi-ortho-shipuchie-30-sht'
                  ],
                  [
                      'id'=>'15824',
                      'name'=>'Прайм Бонд UNIVERSAL mini refil (2,5 мл) Адгезив универсальный, Dentsply',
                      'img'=>'https://aveldent.ru/image/cache/460/import-files-f0-f0e013ec-ff89-11ed-80f1-0cc47aab4f67-f0e01413-ff89-11ed-80f1-0cc47aab4f67.png-460x460.png',
                      'price'=>'1400',
                      'href'=>'https://aveldent.ru/stomatologicheskie-materiali/adgezivnie-materialy/adgezivi/prajm-bond-universal-mini-refil-2-5-ml-adgeziv-universalnij-dentsply'
                  ]
              ]
        ]];
       return redirect('/send?data='.json_encode($data,true));
    }



    public function sendEmail(Request $request){

        //добавить колонку с id отправителем (почтой) добавить таблицу с отправителями и сделать проверку какой отправитель был раньше и отправлять с другого

        $data= $this->getValEmail($request);
        if(!empty($data)){

            $pattern=$data['pattern'];
            $email=$data['email'];
            $title=$data['title'];
            $products=$data['data']['product'];
            $name=$data['data']['name'];
            $add_value=$data['data']['add_value'];
            $view= View::where('name','=',$pattern)->where('status','=',1)->first();//берем данные шаблона и ищем в нем переменные разделенные | и заменяем на настоящие переменные и передаем в шаблон
          if (empty($view['data'])){
              return;
          }
            $view=$view['data'];
//            $content = str_replace("|Name|",$name, $view);
//            $content = str_replace("|[product]|",$html_product, $content);

//ПОЛУЧЕНИЕ ПОСЛЕДНЕЙ ЗАПИСИ ИЗ ТАБЛИЦЫ ВХОДЯЩИХ ПОЧТ
            $mytime = Carbon::now();
            $counter=1;
            $sender=1;
            $date_send=$mytime;
            $pause_sec=1;
            $pause=false;
            $hash_create=hash('md5', $email.$pattern.$mytime);
            $content=BladeCompiler::render($view,['products'=>$products,'Name'=>$name,'add_value'=>$add_value,'title'=>$title,'Email'=>$email,'disabled_href'=>env('APP_URL').'/Email_disabled?data='.$hash_create]);


            $last_record=DB::table('email_queue')->latest('created_at')->first();
            if(!empty($last_record)){
                $counter=$last_record->counter + 1;
                $date_record=Carbon::parse($last_record->created_at);

               if($date_record->toArray()['day'] < $mytime->toArray()['day']){//Если день сменился обновляем счетчик
                   $counter=1;
               }

               if($last_record->sender == 1){//смена отправителей письма
                   $sender=2;
               }


               if($counter >= 600){//если запись по счету 600 запись тогда устанавливаем время отправки на следующий день в 8 утра и делаем задержку в очереди
                   $next_day=$date_record->addDay(1);
                   $next_day->hour   = 8;
                   $next_day->minute = 0;
                   $next_day->second = 0;
                   $totalDuration = $next_day->diffInSeconds($mytime);
                   $pause_sec=$totalDuration;//на сколько секунд задержать очередь
                   $date_send=$next_day;//меняем время отправки на 8 утра
                   $pause=true;

               }

            }


            $email_queue= new Email_queue();//передаем данные по письму в таблицу
            $email_queue->email=$email;
            $email_queue->pattern=$pattern;
            $email_queue->hash=$hash_create;
            $email_queue->status=0;
            $email_queue->pause=$pause;
            $email_queue->counter=$counter;
            $email_queue->sender=$sender;
            $email_queue->date_send=$date_send;
            $email_queue->save();
            $id_record=$email_queue->id;

            $content = str_replace("href=\"https://aveldent.ru","href=\"".env('APP_URL')."/redirect?user=".$hash_create."&data=", $content);//ищем ссылки и заменяем их на путь к нашему почтовому клиенту для рассчета на что кликнул пользователь и добавляем id для идентификации пользователя

            ProcessEmailSend::dispatch($content,$email,$title,$id_record,$sender)->delay($pause_sec);

        }
    }

    public function redirect(Request $request){
        $data=$request->all();
        if(!empty($data['user']) && !empty($data['data'])){//обработка, запись лога и редирект
            $Email= Email_queue::where('hash',$data['user'])->first();
            if($Email){
                $log=new Log();
                $log->user_id=$Email->id;
                $log->hash=$data['user'];
                $log->href=$data['data'];
                $log->save();
            }

          return  redirect('https://aveldent.ru'.$data['data']);
        }
    }

    public function disabledEmail(Request $request){

        $data=$request->all();

        if ($data['data']){
            $email=Email_queue::where('hash','=',$data['data'])->first();
            if($email){
                if(Email_disabled::where('email','=',$email->email)->first()){
                    return view('disabledEmail',['data'=>'Вы уже отписались от всех наших email-рассылок']);
                }
                $email_disabled= new Email_disabled();
                $email_disabled->email=$email->email;
                $email_disabled->save();
                return view('disabledEmail',['data'=>'Вы отписались от всех наших email-рассылок']);
            }
        }
    }




}
