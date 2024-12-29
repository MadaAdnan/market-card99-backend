<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OneSignalAllUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $data)
    {
        //

        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $app_id = '1680d42f-3400-4bb5-a684-618e9d8d6cf6';
        $rest_api_key = 'MDJmMmU4NDYtMTAwYS00ZDg0LThmNjktMDk1MTNlYzkzNjEy';

// بيانات الإشعار
        $message = array(
            'en' => $this->data['body'],

        );



// بناء الجسم للطلب
        $fields = array(
            'app_id' => $app_id,
            'included_segments' => ['All'],
            'contents' => $message,
            'web_icon'=>isset($this->data['img'])?$this->data['img']:Setting::first()?->getFirstMediaUrl('image'),

        );

        $fields = json_encode($fields);

// رأس الطلب
        $headers = array(
            'Authorization: Basic ' . $rest_api_key,
            'Content-Type: application/json'
        );

// إرسال الطلب باستخدام cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

// معالجة الاستجابة
        if ($response === false) {
            die('Curl failed: ' . curl_error($ch));
        }
        $response_data = json_decode($response, true);
        if (isset($response_data['errors'])) {

            echo ('Notification send error: ' . $response_data['errors'][0]);
        }else{
            echo 'Notification sent successfully';
        }


    }
}
