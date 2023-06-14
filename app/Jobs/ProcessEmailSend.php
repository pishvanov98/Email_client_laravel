<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\EmailController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\View\Compilers\BladeCompiler;


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
//        $content = $this->message;
//        Mail::mailer('smtp3')->send('default',['content'=>$content],function ($message){
//            $message->to($this->email,'AvelDent.ru');//кому
//            $message->from('noreply-3@aveldent.ru','AvelDent.ru');//от
//            $message->subject($this->title);
//        });
//
//        DB::table('email_queue')
//            ->where('id', '=', $this->id_record)
//            ->update(['status' => '1']);

    }

}
