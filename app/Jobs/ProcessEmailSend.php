<?php

namespace App\Jobs;
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
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
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
            $message->to('nikita@aveldent.ru','to dev block')->subject('test mail');//кому
            $message->from('nikita@aveldent.ru','to dev block22')->subject('test mail');//от
        });
    }
}
