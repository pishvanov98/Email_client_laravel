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
        $data= $this->getValEmail($request);
        $pattern=$data['pattern'];
        $email=$data['email'];
        $title=$data['title'];
        $products=$data['data']['product'];
        $name=$data['data']['name'];
        $html_product='<table class="2b1204727b46989523cbbfc4b6c8f39brow" style="background:center top;border-collapse:collapse;border-spacing:0;display:block;padding:0px;text-align:left;vertical-align:top;width:100%"><tbody><tr align="left" style="padding:0;text-align:left;vertical-align:top">';
        foreach ($products as $product){

            $html_product .='<td class="375efcce6d4b9c809c07345079e8e4cwrapper a6c32111e79bc6a63b443fc4136407b1first" align="left" valign="top" style="border-collapse:collapse !important;color:#605d5f;sans-serif;font-size:18px;font-weight:normal;margin:0;padding:0px;text-align:left;vertical-align:top;word-break:break-word">';
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
            $html_product .='</td>';

        }
        $html_product .='</tr></tbody></table>';
        $view= View::all()->where('name','=',$pattern)->toArray();//берем данные шаблона и ищем в нем переменные разделенные | и заменяем на настоящие переменные и передаем в шаблон
        $view=$view[0]['data'];
        $content = str_replace("|Name|",$name, $view);
        $content = str_replace("|[product]|",$html_product, $content);

        $this->dispatch(new ProcessEmailSend($content,$email,$title));

    }



}
