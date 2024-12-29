<?php

use App\Models\Setting;
use App\Notifications\SendNotificationDB;

if(!function_exists('getSettings')){
    function getSettings($field){
        return Setting::first()?->$field;
    }
}
function getSettingsModel(){
    return Setting::first();
}

function sendNotificationToUSer(\App\Models\User $user,$type='success',$bill=null,$options=[]){
  if(count($options)==0){
      $data=['route'=>''];
      if($type=='success'){
          $data['color']='green';
      }//
      elseif ($type=='error'){
          $data['color']='red';
      }//
      else{
          $data['color']='black';
      }
      if($bill!=null){
          $data['img']=$bill->product->getImage();
          if($type=='success'){
              $data['title'] = ' قبول الطلب ✔';
              $data['body'] = 'تم قبول طلبك ' . $bill->product->name;
          }//
          elseif ($type=='error'){
              $data['title'] = 'إلغاء الطلب ❌';
              $data['body'] = 'تم إلغاء طلبك ' . $bill->product->name;
          }//
      }//
  }else{
      $data=array_merge($options,['color'=>'black']);
  }

    $user->notify(new SendNotificationDB($data));
}
