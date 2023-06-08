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
        $data= $this->getValEmail($request);
        $pattern=$data['pattern'];
        $email=$data['email'];
        $products=$data['data']['product'];
        $name=$data['data']['name'];
        $html_product='';
        foreach ($products as $product){
            $html_product=$html_product.'<a href="'.$product['href'].'"><img src="'.$product['img'].'"><p>'.$product['name'].'</p><span>'.$product['price'].'</span></a>';
        }
        $view= View::all()->where('name','=',$pattern)->toArray();//берем данные шаблона и ищем в нем переменные разделенные | и заменяем на настоящие переменные и передаем в шаблон
        $view=$view[0]['data'];
        $content = str_replace("|Name|",$name, $view);
        $content = str_replace("|[product]|",$html_product, $content);

        $this->dispatch(new ProcessEmailSend($content));

    }



}
