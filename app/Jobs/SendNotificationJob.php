<?php

namespace App\Jobs;

use App\Mail\Site\Mails\SampleMail;
use App\Models\User;
use App\Notifications\SendNotificationDB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use OneSignal;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $data)
    {
        $this->users = $users;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Mail::to($this->users)->send(new SampleMail($this->data));
            if ($this->users instanceof User) {
                $userId = $this->users->device_token;
                $params = [];
                $params['included_segments'] = ['All'];
                $params['filters'] = [
                    [
                        'field' => 'tag',
                        'key' => 'email',
                        'relation' => '=',
                        'value' =>$this->users->email
                    ]
                ];
                $contents = [

                    "en" => $this->data['body'],

                ];
                $params['contents'] = $contents;
                /*$params['delayed_option'] = "timezone"; // Will deliver on user's timezone
                $params['delivery_time_of_day'] = "2:30PM"; // Delivery time*/

                \OneSignal::sendNotificationCustom($params);
            } else {
                $params = [];
                //$params['include_player_ids'] = $this->users->pluck('device_token')->whereNotNull('device_token')->toArray();
                $params['included_segments'] = ['All'];
                $params['filters'] = [
                    [
                        'field' => 'tag',
                        'key' => 'email',
                        'relation' => 'exists',
                        'value' =>$this->users->pluck('email')->toArray()
                    ]
                ];
                $contents = [

                    "en" => $this->data['body'],

                ];
                $params['contents'] = $contents;
                /*$params['delayed_option'] = "timezone"; // Will deliver on user's timezone
                $params['delivery_time_of_day'] = "2:30PM"; // Delivery time*/

                \OneSignal::sendNotificationCustom($params);

            }


        } catch (\Exception $e) {

        }

    }
}
