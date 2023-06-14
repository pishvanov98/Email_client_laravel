<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ProcessEmailSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $message;
    protected $email;
    protected $title;
    protected $id_record;
    protected $sender;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message,$email,$title,$id_record,$sender)
    {
        $this->message = $message;
        $this->email = $email;
        $this->title = $title;
        $this->id_record = $id_record;
        $this->sender = $sender;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->message;

        if ($this->sender == 1){
            Mail::mailer('smtp1')->send('default',['content'=>$content],function ($message){
                $message->to($this->email,'AvelDent.ru');//кому
                $message->from('noreply-1@aveldent.ru','AvelDent.ru');//от
                $message->subject($this->title);
            });
        }else{
            Mail::mailer('smtp2')->send('default',['content'=>$content],function ($message){
                $message->to($this->email,'AvelDent.ru');//кому
                $message->from('noreply-2@aveldent.ru','AvelDent.ru');//от
                $message->subject($this->title);
            });
        }



//        Mail::mailer('smtp1')->to('example@shouts.dev')->send(new SendTestMail());
//        Mail::mailer('smtp2')->to('example@shouts.dev')->send(new SendTestMail());

        DB::table('email_queue')
            ->where('id', '=', $this->id_record)
            ->update(['status' => '1']);
    }


    public function failed()
    {
//        Artisan::call('queue:restart');
//        $path = base_path('.env');
//        if (file_exists($path)) {
//            file_put_contents($path, str_replace(
//                'MAIL_USERNAME='.env('MAIL_USERNAME'), 'MAIL_USERNAME=nikita@aveldent.ru', file_get_contents($path)
//            ));
//            file_put_contents($path, str_replace(
//                'MAIL_PASSWORD='.env('MAIL_PASSWORD'), 'MAIL_PASSWORD=cspduqgbrxbsppwp', file_get_contents($path)
//            ));
//        }
//        Artisan::call('queue:work --tries=1');
//
//        Artisan::call('cache:clear');
//        Artisan::call('config:clear');
//        Artisan::call('queue:work --tries=3');
//        Artisan::call('queue:restart');
//        Artisan::call('queue:work --tries=1');
    }

}
