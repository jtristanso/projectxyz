<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
class Email implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $account;
    protected $type;
    protected $template;
    protected $header = "</<!DOCTYPE html>
        <html>
        <head>
            <title>Email Confirmation</title>
            <style>
                .header, .text{
                    width: 90%;
                    float:left;
                    min-height: 10px;
                    overflow-y: hidden;
                    margin: 0 5% 0 5%;
                }
                .header{
                    margin-top: 25px;
                }
                .header img{
                    float: left;
                    margin-right: 10px;
                }
                .header span{
                    font-size: 40px;
                    font-weight: 600;
                    float: left;
                }
                .text-primary{
                    color:#3f0050;   
                }
                .text-secondary{
                    color:#028170;
                }
                .text{
                    color: #555;
                    font-size: 18px;
                }
                .button{
                    width: 300px;
                    height: 50px;
                    background: #3f0050;
                    border: none;
                    color: #fff;
                    font-size: 16px;
                    margin-top: 25px;
                    margin-bottom: 25px;
                    border-radius: 5px;
                }
                .button:hover{
                    cursor: pointer;
                }
                .footer{
                    width: 100%;
                    margin: 25px 0 25px 0;
                    border-top: solid 1px #aaa;
                    text-align: center;
                    float: left;
                }
                .footer label{
                    margin-top: 10px;
                    color: #aaa;
                }
            </style>
        </head>
        <body>";
    protected $footer = "<span class=footer>
                <label>Copyright @CLASSWORX.CO 2018</label>
                <br>
                <label>Cebu City, Philippines</label>
            </span>
        </body>
        </html>";
    public function __construct($account, $type)
    {
        $this->account = $account;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $to = $this->account['email'];
        $subject = '';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: ClassWorx<support@classworx.co>' . "\r\n";
        $message = '';
        switch ($this->type) {
            case 'verification':
                $subject = 'Email Verification';
                $message = $this->verificationTemplate();
                break;
            case 'request_reset':
                $subject = 'Reset Password Request';
                $message = $this->recoveryTemplate();
                break;
            case 'reset_password':
                $subject = 'Password Changed';
                $message = $this->changedPasswordTemplate();
                break;
            case 'login':
                $subject = 'New Login Notification';
                $message = $this->loginTemplate();
                break;
        }

        $result = mail($to,$subject,$message,$headers);
        if($result){
            // Success
        }else{
            // Failed
        }
    }

    public function recoveryTemplate(){
        return $this->header."
            <span class=header>
                <img src=http://api.classworx.co/api/public/storage/logo/classworx.png height=40px width=40px>
                <span><label class=text-primary>CLASS</label><label class=text-secondary>WORX</label></span>
            </span>
            <span class=text>
            <h3>Change Password Request</h3>
            Hello ".$this->account['username']."! 
            <br>
            You've requested to reset your account password on Classworx using this email address at ".$this->account['email'].".
            <br>
            Click the button below to:
            <br>
            <a href=http://classworx.co/#/reset_password/".$this->account['username']."/".$this->account['code'].">
                <button class=button>Reset</button>
            </a>
            <br>
            </span>
            <span class=text>
            <b>Didn't request this email?</b>
            <br>
            Don't Worry! Your Email Address have been entered by mistake. If you ignore or delete this email and nothing will happen.
            </span>
        ".$this->footer;
    }
    public function verificationTemplate(){
        return $this->header."
            <span class=header>
                <img src=http://api.classworx.co/api/public/storage/logo/classworx.png height=40px width=40px>
                <span><label class=text-primary>CLASS</label><label class=text-secondary>WORX</label></span>
            </span>
            <span class=text>
            <h3>Confirm your email address on Classworx</h3>
            Hello ".$this->account['username']."! We just need to verify that <b>".$this->account['email']."</b> is your email address.
            <br>
            Click the button below to:
            <br>
            <a href=http://classworx.co/#/login_verification/".$this->account['username']."/".$this->account['code'].">
                <button class=button>Confirm</button>
            </a>
            <br>
            </span>
            <span class=text>
            <b>Didn't request this email?</b>
            <br>
            Don't Worry! Your Email Address have been entered by mistake. If you ignore or delete this email and nothing will happen.
            </span>
        ".$this->footer;
    }

    public function changedPasswordTemplate(){
        return $this->header."
            <span class=header>
                <img src=http://api.classworx.co/api/public/storage/logo/classworx.png height=40px width=40px>
                <span><label class=text-primary>CLASS</label><label class=text-secondary>WORX</label></span>
            </span>
            <span class=text>
            <h3>Password Changed</h3>
            Hello ".$this->account['username']."! Your password was changed.
            <br>
            Password was changed on ".Carbon::now().".
            <br>
            </span>
            <span class=text>
            If you did not make this change, please <a href=http://classworx.co/#/reset_password/".$this->account['username']."/".$this->account['code'].">reset</a> your password to secure your account and reply to this message to notify us.
            </span>
        ".$this->footer;
    }

    public function loginTemplate(){
        return $this->header."
            <span class=header>
                <img src=http://api.classworx.co/api/public/storage/logo/classworx.png height=40px width=40px>
                <span><label class=text-primary>CLASS</label><label class=text-secondary>WORX</label></span>
            </span>
            <span class=text>
            <h3>New Login</h3>
            Hello ".$this->account['username']."!
            <br>
            New Login on ".Carbon::now().".
            <br>
            </span>
            <span class=text>
            If this is not you, please <a href=http://classworx.co/#/reset_password/".$this->account['username']."/".$this->account['code'].">reset</a> your password to secure your account and reply to this message to notify us.
            </span>
        ".$this->footer;
    }
}
