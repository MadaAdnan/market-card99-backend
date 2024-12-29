<?php

namespace App\Observers;

use App\Jobs\OneSignalAllUserJob;
use App\Jobs\OneSignalJob;
use App\Jobs\SendHookJob;
use App\Jobs\SendNotificationJob;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\SendNotificationDB;

class ProductObserve
{
    public $afterCommit = true;
    /**
     * Handle the Product "created" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function created(Product $product)
    {

       $job=new SendHookJob($product);
        dispatch($job);
    }

    /**
     * Handle the Product "updated" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function updated(Product $product)
    {
      $job=new SendHookJob($product);
        dispatch($job);

        if($product->is_notify && $product->isDirty('is_available')){
            $arr['route'] = '';
            $arr['title'] = 'تعديل الحالة';
            $arr['img']=$product->getImage();
            if ( $product->is_available) {
                $arr['body'] = 'أصبح المنتج ' . $product->name . ' مـتـ✓ـوفر الآن يمكنك الشراء';
            } else {
                $arr['body'] = 'للأسف أصبح المنتج ' . $product->name . ' غير متـ✘ــوفر الآن سيتم توفره في أسرع وقت';
            }
            // \Notification::send($users, new SendNotificationDB($data));
            $job = new OneSignalAllUserJob( $arr);
            dispatch($job);
        }
        elseif($product->is_notify && $product->isDirty('cost')){
            $arr['route'] = '';
            $arr['title'] = 'تعديل سعر';
            $arr['img']=$product->getImage();
            $arr['body'] = 'تم تحديث سعر المنتج ' . $product->name . 'يرجى الإطلاع على السعر الجديد';
            $job = new OneSignalAllUserJob( $arr);
            dispatch($job);
        }

        if($product->is_notify){
            $product->update([
                'is_notify'=>false
            ]);
        }
    }

    /**
     * Handle the Product "deleted" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
