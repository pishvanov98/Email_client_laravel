<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmailSend;
use App\Models\Email_queue;
use App\Models\Log;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public $count=0;
    public function getValEmail($request){
        $data_request=$request->all();
        $email_mass=[];
        if(!empty($data_request['data'])){
            $data=json_decode($data_request['data'],true);
                if($data['token'] == 'f2354a65cc810f0a73a0160a3e25628a' && !empty($data['email']) && !empty($data['title']) && !empty($data['data']) && !empty($data['pattern'])){
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
          'token'=>md5('Avel'),
          'email'=>'nikita@aveldent.ru',
          'title'=>'Поступление товара',
          'pattern'=>'entrance',//поступление товара
          'data'=>[
              'name'=>'Никита',
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
            $html_product='<table class="2b1204727b46989523cbbfc4b6c8f39brow" style="background:center top;border-collapse:collapse;border-spacing:0;padding:0px;text-align:left;vertical-align:top;width:100%;"><tbody>';
            foreach ($products as $product){

                $html_product .='<tr align="left" style="padding:0;text-align:left;vertical-align:top;"><td class="375efcce6d4b9c809c07345079e8e4cwrapper a6c32111e79bc6a63b443fc4136407b1first" align="left" valign="top" style="border-collapse:collapse !important;color:#605d5f;sans-serif;font-size:18px;font-weight:normal;margin:0;padding:0px;text-align:left;vertical-align:top;word-break:break-word">';
                $html_product .='<table class="d799f0b8852cefa66c0002ab833759fsix c811caeafd0ee479775e15c67896ff48columns" style="border-collapse:collapse;border-spacing:0;margin:0 auto 0 auto;padding:0;text-align:left;vertical-align:top;width:290px"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top"><td align="left" valign="top" style="border-collapse:collapse !important;color:#605d5f;font-size:18px;font-weight:normal;margin:0;padding:0px;text-align:left;vertical-align:top;width:100%;word-break:break-word">';
                $html_product .='<table align="left" style="border:0;border-collapse:collapse;border-spacing:0;overflow:hidden;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top"><td align="left" valign="top" style="border-collapse:collapse !important;color:#605d5f;font-size:18px;font-weight:normal;margin:0;padding:0px;text-align:left;vertical-align:top;width:100%;word-break:break-word">';
                $html_product .='<a href="'.$product['href'].'" rel="noopener noreferrer" target="_blank" style="color:#605d5f;text-decoration:none" data-link-id="8">';
                $html_product .='<img class="7ef4d82bcbce760d187059a381e2eebaleft" align="left" alt="'.$product['img'].'" height="314" src="'.$product['img'].'" width="290" style="border:medium;clear:both;display:block;float:left;height:314px;max-width:100%;text-decoration:none;width:290px">';
                $html_product .='</a>';
                $html_product .='</td></tr></tbody></table>';
                $html_product .='<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top"><td class="" align="left" valign="top" style="background:center center;border-collapse:collapse !important;color:#605d5f;font-size:1px;font-weight:normal;line-height:0;margin:0;padding:10px 0px 0px 0px;text-align:left;vertical-align:top;width:100%;word-break:break-word">&nbsp;</td></tr></tbody></table>';
                $html_product .='<table style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top;width:100%"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top"><td class="" align="left" valign="top" style="background:center center;border-collapse:collapse !important;color:#605d5f;font-size:1px;font-weight:normal;line-height:0;margin:0;padding:20px 0px 0px 0px;text-align:left;vertical-align:top;width:100%;word-break:break-word">&nbsp;</td></tr></tbody></table>';
                $html_product .='<table class="d982e69559c106e6c42f6ca640031c43table-block" width="100%" style="border-collapse:collapse;border-spacing:0;padding:0;text-align:left;vertical-align:top"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top"><td class="" align="left" valign="top" style="background:center center;border-collapse:collapse !important;color:#605d5f;font-size:18px;font-weight:normal;margin:0;padding:0px 10px 0px 10px;text-align:left;vertical-align:top;width:100%;word-break:break-word">';
                $html_product .='<p align="center" style="color:#605d5f;font-size:18px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;padding:0;text-align:center"><span style="color:#808080;font-size:18px">'.$product['name'].'</span><span style="color:#808080;font-size:18px">&nbsp;</span></p>';
                $html_product .='<p align="center" style="color:#605d5f;font-size:18px;font-weight:normal;line-height:1.3;margin:0 0 10px 0;padding:0;text-align:center"><span style="color:#f65d8b"><strong>'.$product['price'].' руб.</strong></span></p>';
                $html_product .='</td></tr></tbody></table>';
                $html_product .='<table class="d9e828a2be6b42b8815d836d1c464417button 4c13a6fd171176a9b1cf16866dc48ab2center" style="border-collapse:collapse;border-spacing:0;overflow:hidden;padding:0;text-align:center;vertical-align:top;width:100%"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top"><td align="center" valign="top" style="border-collapse:collapse !important;color:#605d5f;display:block;font-size:18px;font-weight:normal;line-height:initial !important;margin:0;padding:0px;text-align:center;vertical-align:top;width:auto !important;word-break:break-word">';
                $html_product .='<a href="'.$product['href'].'" rel="noopener noreferrer" target="_blank" style="background:#008080 center center;border-radius:0px;color:#ffffff;display:inline-block;font-size:16px !important;font-weight:bold;height:100%;line-height:16px;padding:9px 20px 9px 20px;text-align:center;text-decoration:none;width:auto" data-link-id="9">Купить</a>';
                $html_product .='</td></tr></tbody></table>';
                $html_product .='</td><td class="a5cce8faeed789fc65b723bbec3172d0expander" align="left" valign="top" style="border-collapse:collapse !important;color:#605d5f;font-size:18px;font-weight:normal;margin:0;padding:0;text-align:left;vertical-align:top;visibility:hidden;width:100%;word-break:break-word"></td></tr></tbody></table>';
                $html_product .='</td></tr>';

            }
            $html_product .='</tbody></table>';
            $view= View::all()->where('name','=',$pattern)->toArray();//берем данные шаблона и ищем в нем переменные разделенные | и заменяем на настоящие переменные и передаем в шаблон
            $view=$view[0]['data'];
            $content = str_replace("|Name|",$name, $view);
            $content = str_replace("|[product]|",$html_product, $content);

//ПОЛУЧЕНИЕ ПОСЛЕДНЕЙ ЗАПИСИ ИЗ ТАБЛИЦЫ ВХОДЯЩИХ ПОЧТ
            $mytime = Carbon::now();
            $counter=1;
            $sender=1;
            $date_send=$mytime;
            $pause_sec=1;
            $pause=false;

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

            $hash_create=hash('md5', $email.$pattern.$mytime);
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

            $content = str_replace("href=\"https://aveldent.ru","href=\"http://127.0.0.1:8000/redirect?user=".$hash_create."&data=", $content);//ищем ссылки и заменяем их на путь к нашему почтовому клиенту для рассчета на что кликнул пользователь и добавляем id для идентификации пользователя
echo $pause_sec;
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



}
