<?php

namespace App\Rpositories;

use App\Enums\BillStatusEnum;
use App\FromApi\EkoCard;
use App\FromApi\LifeCash;
use App\FromApi\SpeedCard;
use App\Jobs\SendNotificationJob;
use App\Models\Balance;
use App\Models\Bill;
use App\Models\Point;
use App\Models\Setting;
use App\Notifications\SendNotificationDB;
use Illuminate\Support\Facades\DB;

class BillRepository
{
    public static function complateBill(Bill $bill, $other_data = null)
    {
        $setting = Setting::first();
        DB::beginTransaction();
        try {
            $bill->update(['status' => BillStatusEnum::COMPLETE->value, 'data_id' => $other_data]);
            $parent = $bill->user->user;
            $affiliate_user_id = $bill->user->affiliate_id;
            $branch_ratio=0;
            $branch= $bill->user->user?->user;
            if($branch!=null){
                $branch_ratio=($setting->branch_ratio * $bill->ratio);
            }

            if ($parent != null && $bill->ratio > 0) {
                Point::create([
                    'credit' => $bill->ratio-$branch_ratio,
                    'debit' => 0,
                    'info' => 'ربح من مبيع ' . $bill->user->name,
                    'user_id' => $parent->id
                ]);

            }

            if ($affiliate_user_id != null && $setting->is_affiliate) {
                Point::create([
                    'credit' => ($setting->affiliate_ratio * $bill->price)-$branch_ratio,
                    'debit' => 0,
                    'info' => 'ربح من بيع بالعمولة',
                    'user_id' => $affiliate_user_id
                ]);
            }

            if(!$bill->product->is_offer && $branch !=null && $branch->is_branch ){

                if($branch_ratio>0){
                    Point::create([
                        'credit' => $branch_ratio,
                        'user_id' => $branch->id,
                        'debit' => 0,
                        'info' => 'ربح عن طريق ' . auth()->user()->name,
                        'bill_id'=>$bill->id,
                    ]);
                }

            }

            DB::commit();
            try {
                SendNotificationToUser($bill->user, 'success', $bill);
            } catch (\Exception | \Error $ex) {

            }
        } catch (\Exception | \Error $e) {

            DB::rollBack();
        }

    }

    public static function cancelBill(Bill $bill, $other_data = null)
    {
        DB::beginTransaction();
        try {

            if ($bill->status->value == BillStatusEnum::COMPLETE->value) {
                if ($bill->user->user != null) {
                    Point::create([
                        'debit' => $bill->ratio,
                        'credit' => 0,
                        'info' => 'إعادة نسبة ربح طلب  ' . $bill->product->name,
                        'user_id' => $bill->user->user->id,
                        'bill_id' => $bill->id,
                    ]);
                } elseif ($bill->user->affiliate_user != null) {
                    Point::create([
                        'debit' => $bill->ratio,
                        'credit' => 0,
                        'info' => 'إعادة نسبة ربح طلب  ' . $bill->product->name,
                        'user_id' => $bill->user->affiliate_user->id,
                        'bill_id' => $bill->id,
                    ]);
                }

                Balance::create([
                    'credit' => $bill->price,
                    'total' => $bill->user->balance + $bill->price,
                    'ratio' => $bill->ratio,
                    'debit' => 0,
                    'info' => 'إعادة قيمة طلب  ' . $bill->product->name,
                    'user_id' => $bill->user_id,
                    'bill_id' => $bill->id,
                ]);

            }
            elseif ($bill->status->value != BillStatusEnum::CANCEL->value) {
                Balance::create([
                    'credit' => $bill->price,
                    'total' => $bill->user->balance + $bill->price,
                    'ratio' => $bill->ratio,
                    'debit' => 0,
                    'info' => 'إعادة قيمة طلب  ' . $bill->product->name,
                    'user_id' => $bill->user_id,
                    'bill_id' => $bill->id,
                ]);

            }
           $bill->points()->delete();
        /*     $bill->balances()->delete();*/
            $bill->update(['status' => BillStatusEnum::CANCEL->value, 'cancel_note' => $other_data]);
            DB::commit();
            try {
                SendNotificationToUser($bill->user, 'error', $bill);
            } catch (\Exception | \Error $ex) {

            }
        } catch (\Exception | \Error $e) {

            DB::rollBack();
        }


    }
}
