<?php

namespace App\Jobs;
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
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message,$email,$title,$id_record)
    {
        $this->message = $message;
        $this->email = $email;
        $this->title = $title;
        $this->id_record = $id_record;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $content = $this->message;
        Mail::send('default',['content'=>$content],function ($message){
            $message->to($this->email,'AvelDent.ru');//кому
            $message->from('nikita@aveldent.ru','AvelDent.ru');//от
            $message->subject($this->title);
        });

        DB::table('email_queue')
            ->where('id', '=', $this->id_record)
            ->update(['status' => '1']);

    }
}
