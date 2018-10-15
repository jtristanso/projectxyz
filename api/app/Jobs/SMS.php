<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $account;
    protected $action;
    protected $key = '33281737';
    protected $secret = 'r9pjlQUrEnyoj8AF';
    public function __construct($account, $action)
    {
        $this->account = $account;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->action) {
            case 'notification':
                $this->notification();
                break;
            
            default:
                # code...
                break;
        }
    }

    public function notification(){
        $from = 'ClassWorx';
        $to = $this->account['informations']['cellular_number'];
        $data = array(
            'from'  => 'CLASSWORX',
            'to'    => $this->account['informations']['cellular_number'],
            'message-id' => $this->generateCode()
        );
    }

    public function generateCode(){
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 32);
    }
}
