<?php

namespace App\Http\Controllers\Api2;

use App\CheckOrder;
use App\Enums\BillStatusEnum;
use App\Enums\ProductTypeEnum;
use App\FromApi\As7ab;
use App\FromApi\CachBack;
use App\FromApi\Drd3;
use App\FromApi\EkoCard;
use App\FromApi\LifeCash;
use App\FromApi\Mazaya;
use App\FromApi\SaudCard;
use App\FromApi\SpeedCard;
use App\Http\Controllers\Controller;
use App\Http\Middleware\RateLimit;
use App\Http\Resources\Api2\BillResource;
use App\Http\Resources\Api2\UserResource;
use App\Models\Balance;
use App\Models\Bill;
use App\Models\Black;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Point;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Support\HelperSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class BillController extends Controller
{

    public function __construct()
    {
        $this->middleware(RateLimit::class)->only('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = \request()->input('type');
        $search = \request()->input('search');
        $bills = Bill::where('user_id', auth()->id())
            ->when(!empty($type), fn($query) => $query->where('status', $type))
            ->where(fn($query) => $query->where('id_bill', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->orWhere('customer_id', 'like', '%' . $search . '%')
                ->orWhere('customer_username', 'like', '%' . $search . '%')
                ->orWhere('data_username', 'like', '%' . $search . '%')
                ->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            )
            ->latest()->paginate();
        $total_month = Bill::where(['user_id' => auth()->id(), 'status' => BillStatusEnum::COMPLETE->value])->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('price');
        $total_pre_month = Bill::where(['user_id' => auth()->id(), 'status' => BillStatusEnum::COMPLETE->value])->whereBetween('created_at', [now()->startOfMonth()->subDay()->startOfMonth(), now()->startOfMonth()->subDay()->endOfMonth()])->sum('price');
        return HelperSupport::sendData(['bills' => BillResource::collection($bills),
            'total_now' => $total_month ?? 0,
            'total_back' => $total_pre_month ?? 0,
            'total_page' => $bills->lastPage()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $hash = auth()->user()->hash;
        if (auth()->user()->is_hash && $hash != $request->old) {
            HelperSupport::SendError('خطأ في كلمة التأكيد', 'خطأ في كلمة التأكيد');
        }
        $product = Product::find($request->product_id);
        if (!$product || !$product->is_available || !$product->active || !$product->category->is_available || !$product->category->active) {
            return HelperSupport::SendError('خطأ في الطلب', 'المنتج غير متوفر حاليا');
        }
        try {
            /*
            * Buy Product
            */

            if ($product->type->value == 'default') {

                if (auth()->user()->getTotalBalance() < $product->getPrice()) {
                    throw new \Exception('لا تملك رصيد كافي للشراء');

                }//
                $invoice = Invoice::create([
                    'user_id' => auth()->id(),
                    'status' => BillStatusEnum::COMPLETE->value,
                    'code' => uniqid('M', true),
                    'total' => $product->getPrice() * $request->count
                ]);
                $item = Item::active()->where('product_id', $product->id)->first();
                if ($item) {
                    \DB::beginTransaction();
                    try {
                        $data = [
                            'user_id' => auth()->id(),
                            'id_bill' => Str::random(),
                            'price' => $product->getPrice(),
                            'ratio' => 0,
                            'status' => BillStatusEnum::COMPLETE->value,
                            'category_id' => $product->category_id,
                            'cost' => $product->total_cost,
                            'invoice_id' => $invoice->id,
                            'product_id' => $product->id,
                            'data_id' => $item->code,
                        ];
                        if (!$product->is_offer && auth()->user()->user != null) {
                            $ratio = ($product->total_cost * auth()->user()->group->ratio_delegate);
                            $data['ratio'] = $ratio;
                        } elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                            $ratio = ($product->total_cost * getSettings('affiliate_ratio'));
                            $data['ratio'] = $ratio;
                        }
                        $bill = Bill::create($data);
                        $item->update([
                            'bill_id' => $bill->id,
                            'active' => 0,
                        ]);
                        Balance::create([
                            'user_id' => auth()->id(),
                            'info' => 'شراء منتج ' . $product->name,
                            'debit' => $product->getPrice(),
                            'credit' => 0,
                            'total' => auth()->user()->balance - $product->getPrice(),
                            'bill_id'=>$bill->id,
                        ]);
                        //
                        /**
                         * @var $branch User
                         */
                        $branch= auth()->user()->user?->user;
                        if(!$product->is_offer && $branch !=null && $branch->is_branch ){
                            $branch_ratio=  Setting::first()->branch_ratio * $ratio;
                            $ratio-=$branch_ratio;
                            if($branch_ratio>0){
                                Balance::create([
                                    'credit' => $branch_ratio,
                                    'user_id' => $branch->id,
                                    'debit' => 0,
                                    'info' => 'ربح عن طريق ' . auth()->user()->name,
                                    'bill_id'=>$bill->id,
                                ]);
                            }

                        }
                        if (!$product->is_offer && auth()->user()->user != null) {
                            Point::create([
                                'credit' => $ratio,
                                'user_id' => auth()->user()->user_id,
                                'debit' => 0,
                                'info' => 'ربح عن طريق ' . auth()->user()->name,
                                'bill_id'=>$bill->id,
                            ]);
                        }
                        elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                            Point::create([
                                'credit' => $ratio,
                                'user_id' => auth()->user()->affiliate_id,
                                'debit' => 0,
                                'info' => 'ربح عن طريق ' . auth()->user()->name,
                                'bill_id'=>$bill->id,
                            ]);
                        }

                        \DB::commit();
                        return HelperSupport::sendData(['bill' => new BillResource($bill), 'user' => new UserResource(auth()->user())]);
                    } catch (\Exception | \Error $e) {
                        \DB::rollBack();
                        HelperSupport::SendError('خطأ في الطلب', $e->getMessage());
                    }
                } //
                elseif ($product->is_active_api) {
                    if ($product->api == 'life-cash') {
                        $service = new LifeCash(getSettingsModel());
                    }//
                    elseif ($product->api == 'speed-card') {
                        $service = new SpeedCard(getSettingsModel());
                    }//
                    elseif ($product->api == 'drd3') {
                        $service = new Drd3(getSettingsModel());
                    }//
                    elseif ($product->api == 'saud') {
                        $service = new SaudCard(getSettingsModel());
                    } elseif ($product->api == 'eko') {
                        $service = new EkoCard(getSettingsModel());
                    } elseif ($product->api == 'as7ab') {
                        $service = new As7ab(getSettingsModel());
                    }elseif ($product->api == 'mazaya') {
                        $service = new Mazaya(getSettingsModel());
                    }elseif ($product->api == 'cache-back') {
                        $service = new CachBack(getSettingsModel());
                    }

                    // Bill Item From Api
                    \DB::beginTransaction();
                    try {
                        $bill = Bill::create([
                            'id_bill' => Str::uuid()->toString(),
                            'user_id' => auth()->id(),
                            'price' => $product->getPrice(),
                            'category_id' => $product->category_id,
                            'cost' => $product->total_cost,
                            'invoice_id' => $invoice->id,
                            'product_id' => $product->id,
                            'amount' => 1,
                        ]);
                        Balance::create([
                            'user_id' => auth()->id(),
                            'info' => 'شراء منتج ' . $product->name,
                            'debit' => $product->getPrice(),
                            'credit' => 0,
                            'total' => auth()->user()->balance - $product->getPrice(),
                            'bill_id'=>$bill->id,
                        ]);

                        $bill = $service->buyFromApiFixed($bill);
                        $bill->save();
                        \DB::commit();
                        return HelperSupport::sendData(['bill' => new BillResource($bill), 'user' => new UserResource(auth()->user())]);

                    } catch (\Exception $e) {
                        \DB::rollBack();
                        throw new \Exception('حدث خطأ أثناء عملية الشراء ' . $e->getMessage());
                    }

                } else {
                    throw new \Exception('المنتج غير متوفر حاليا', 'المنتج غير متوفر حاليا');
                }


            }
            /*
             * Check Demo Account
             * */
            if (auth()->user()->email == 'market5@gmail.com') {
                $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الوكيل 🖥
لتعبئة الرصيد💲

@212627607678';
                throw new \Exception($msg);
            }//
            elseif (auth()->user()->email == 'market10@gmail.com') {
                $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الوكيل 🖥
لتعبئة الرصيد💲

@905356060166';
                throw new \Exception($msg);
            }
            /*
             * Check any Error
             * */

            if ($request->id_user == '') {
                throw new \Exception('يرجى إضافة ID  الحساب');
            }
            if (!CheckOrder::checkIdPlayer($request->id_user)) {
                throw new \Exception('لا يمكن الطلب لنفس ال ID  قبل مضي 20 ثانية');
            }
            $bill = new Bill();
            if ($product->is_free) {
                if ($request->amount < $product->min_amount) {
                    throw new \Exception('لا يمكن طلب كمية أقل من ' . $product->min_amount);
                }
                if ($request->amount > $product->max_amount) {
                    throw new \Exception('لا يمكن طلب كمية أكبر من ' . $product->max_amount);
                }
                $total_cost = ($product->total_cost / $product->amount) * $request->amount;
                $total_price = ($product->getPrice() / $product->amount) * $request->amount;
                $ratio = 0;
                $bill->amount = $request->amount;
            }//
            else {
                $total_cost = $product->total_cost;
                $total_price = $product->getPrice();
                $ratio = 0;
                $bill->amount = $product->count;

            }
            if (!$product->is_offer && auth()->user()->user != null) {
                $ratio = ($total_cost * auth()->user()->group->ratio_delegate);
                $bill->ratio = $ratio;
            }//
            elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                $ratio = ($total_cost * getSettings('affiliate_ratio'));
                $bill->ratio = $ratio;
            }
            if (auth()->user()->getTotalBalance() < $total_price) {
                throw new \Exception('لا تملك رصيد كافي للشراء');

            }//

            $invoice = Invoice::create([
                'status' => BillStatusEnum::PENDING->value,
                'user_id' => auth()->id(),
                'code' => Str::random(6),
                'total' => $total_price,
            ]);

            $bill->id_bill = Str::uuid()->toString();
            $bill->price = $total_price;
            $bill->cost = $total_cost;
            $bill->user_id = auth()->id();
            $bill->status = BillStatusEnum::PENDING->value;
            $bill->category_id = $product->category_id;
            $bill->product_id = $product->id;
            $bill->customer_note = $request->info;
            $bill->invoice_id = $invoice->id;
            ########################
            $black = Black::where('data', trim($request->id_user))->first();
            if ($black) {
                $bill->black_id = $black->id;
            }
            if ($product->type == ProductTypeEnum::NEED_ID) {

                $bill->customer_id = $request->id_user;
                $bill->customer_name = $request->name;


            }//
            elseif ($product->type == ProductTypeEnum::NEED_ACCOUNT) {
                $bill->customer_username = $request->id_user;
                $bill->customer_password = $request->name;


            } else {
                $bill->customer_username = $request->id_user;
                // $bill->customer_password = $request->name;


            }

            #########################3
            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $total_price,
                'credit' => 0,
                'info' => 'شراء منتج ' . $bill->amount . ' ' . $product->name,
                'total' => auth()->user()->balance - $total_price,
                'bill_id'=>$bill->id,
            ]);


            if ($product->is_free && $product->is_active_api && !$black) {

                if ($product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
                } elseif ($product->api == 'drd3') {
                    $service = new Drd3(getSettingsModel());
                }//
                elseif ($product->api == 'saud') {

                    $service = new SaudCard(getSettingsModel());
                } elseif ($product->api == 'eko') {

                    $service = new EkoCard(getSettingsModel());
                } elseif ($product->api == 'as7ab') {

                    $service = new As7ab(getSettingsModel());
                }elseif ($product->api == 'mazaya') {
                    $service = new Mazaya(getSettingsModel());
                }elseif ($product->api == 'cache-back') {
                    $service = new CachBack(getSettingsModel());
                }
                $bill = $service->buyFromApiFree($bill);

            } //
            elseif (!$product->is_free && $product->is_active_api && !$black) {

                if ($product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
                }//
                elseif ($product->api == 'drd3') {
                    $service = new Drd3(getSettingsModel());
                }//
                elseif ($product->api == 'saud') {
                    $service = new SaudCard(getSettingsModel());
                } elseif ($product->api == 'eko') {

                    $service = new EkoCard(getSettingsModel());
                } elseif ($product->api == 'as7ab') {

                    $service = new As7ab(getSettingsModel());
                }elseif ($product->api == 'mazaya') {
                    $service = new Mazaya(getSettingsModel());
                }elseif ($product->api == 'cache-back') {
                    $service = new CachBack(getSettingsModel());
                }
                $bill = $service->buyFromApiFixed($bill);
            }//
            try {
                $bill->save();
            } catch (\Exception | \Error $e) {

            }
            return HelperSupport::sendData([
                'bill' => new BillResource($bill),
                'user' => new UserResource(auth()->user())
            ]);

        } //
        catch (\Exception | \Error $e) {
            HelperSupport::SendError(['msg' => $e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bill = Bill::find($id);
        $setting = Setting::first();
        if ($bill->status->value == BillStatusEnum::PENDING->value) {
            if ($bill->api_id != null) {
                switch ($bill->api) {
                    case 'life-cash':
                        $service = new LifeCash($setting);

                        break;
                    case 'speed-card':
                        $service = new SpeedCard($setting);

                        break;
                    case 'eko':
                        $service = new EkoCard($setting);
                        break;
                    case 'drd3':
                        $service = new Drd3($setting);
                        break;
                    case 'saud':
                        $service = new SaudCard($setting);
                        break;
                    case 'as7ab':
                        $service = new As7ab($setting);
                        break;
                    case 'mazaya':
                        $service = new Mazaya($setting);
                        break;
                    case 'cache-back':
                        $service = new CachBack($setting);
                        break;

                }
                $service->checkStatus($bill);

            }
        }
        return HelperSupport::sendData(['bill' => new BillResource($bill), 'user' => new UserResource(auth()->user())]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function bayFromApi(Request $request)
    {
        /*$hash = auth()->user()->hash;
        if (auth()->user()->is_hash && $hash != $request->old) {
            HelperSupport::SendError('خطأ في كلمة التأكيد', 'خطأ في كلمة التأكيد');
        }*/
        $product = Product::find($request->product_id);
        if (!$product || !$product->is_available || !$product->active || !$product->category->is_available || !$product->category->active) {
            return HelperSupport::SendError('خطأ في الطلب', 'المنتج غير متوفر حاليا');
        }

        if (auth()->user()->email == 'market5@gmail.com') {
            $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الوكيل 🖥
لتعبئة الرصيد💲

@212627607678';
            throw new \Exception($msg);
        }//
        elseif (auth()->user()->email == 'market10@gmail.com') {
            $msg = 'لا تمتلك رصيد كافي ❌
يرجى التواصل مع الوكيل 🖥
لتعبئة الرصيد💲

@905356060166';
            throw new \Exception($msg);
        }
        try {
            /*
            * Buy Product
            */

            if ($product->type->value == 'default') {

                if (auth()->user()->getTotalBalance() < $product->getPrice()) {
                    throw new \Exception('لا تملك رصيد كافي للشراء');

                }//
                $invoice = Invoice::create([
                    'user_id' => auth()->id(),
                    'status' => BillStatusEnum::COMPLETE->value,
                    'code' => uniqid('M', true),
                    'total' => $product->getPrice() * $request->count
                ]);
                $item = Item::active()->where('product_id', $product->id)->first();
                if ($item) {
                    \DB::beginTransaction();
                    try {
                        $data = [
                            'user_id' => auth()->id(),
                            'id_bill' => Str::random(),
                            'price' => $product->getPrice(),
                            'ratio' => 0,
                            'status' => BillStatusEnum::COMPLETE->value,
                            'category_id' => $product->category_id,
                            'cost' => $product->total_cost,
                            'invoice_id' => $invoice->id,
                            'product_id' => $product->id,
                            'data_id' => $item->code,
                        ];
                        if (!$product->is_offer && auth()->user()->user != null) {
                            $ratio = ($product->total_cost * auth()->user()->group->ratio_delegate);
                            $data['ratio'] = $ratio;
                        } elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                            $ratio = ($product->total_cost * getSettings('affiliate_ratio'));
                            $data['ratio'] = $ratio;
                        }
                        $bill = Bill::create($data);
                        $item->update([
                            'bill_id' => $bill->id,
                            'active' => 0,
                        ]);
                        Balance::create([
                            'user_id' => auth()->id(),
                            'info' => 'شراء منتج ' . $product->name,
                            'debit' => $product->getPrice(),
                            'credit' => 0,
                            'total' => auth()->user()->balance - $product->getPrice(),
                            'bill_id'=>$bill->id,
                        ]);
                        //
                        if (!$product->is_offer && auth()->user()->user != null) {
                            Point::create([
                                'credit' => $ratio,
                                'user_id' => auth()->user()->user_id,
                                'debit' => 0,
                                'info' => 'ربح عن طريق ' . auth()->user()->name,
                            ]);
                        } elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                            Point::create([
                                'credit' => $ratio,
                                'user_id' => auth()->user()->affiliate_id,
                                'debit' => 0,
                                'info' => 'ربح عن طريق ' . auth()->user()->name,
                            ]);
                        }
                        \DB::commit();
                        return HelperSupport::sendData(['bill' => new BillResource($bill), 'user' => new UserResource(auth()->user())]);
                    } catch (\Exception | \Error $e) {
                        \DB::rollBack();
                        HelperSupport::SendError('خطأ في الطلب', $e->getMessage());
                    }
                } //
                elseif ($product->is_active_api) {
                    if ($product->api == 'life-cash') {
                        $service = new LifeCash(getSettingsModel());
                    }//
                    elseif ($product->api == 'speed-card') {
                        $service = new SpeedCard(getSettingsModel());
                    }//
                    elseif ($product->api == 'drd3') {
                        $service = new Drd3(getSettingsModel());
                    }//
                    elseif ($product->api == 'saud') {
                        $service = new SaudCard(getSettingsModel());
                    } elseif ($product->api == 'eko') {
                        $service = new EkoCard(getSettingsModel());
                    } elseif ($product->api == 'as7ab') {
                        $service = new As7ab(getSettingsModel());
                    }elseif ($product->api == 'mazaya') {
                        $service = new Mazaya(getSettingsModel());
                    }elseif ($product->api == 'cache-back') {
                        $service = new CachBack(getSettingsModel());
                    }
                    // Bill Item From Api
                    \DB::beginTransaction();
                    try {
                        $bill = Bill::create([
                            'id_bill' => Str::uuid()->toString(),
                            'user_id' => auth()->id(),
                            'price' => $product->getPrice(),
                            'category_id' => $product->category_id,
                            'cost' => $product->total_cost,
                            'invoice_id' => $invoice->id,
                            'product_id' => $product->id,
                            'amount' => 1,
                        ]);
                        Balance::create([
                            'user_id' => auth()->id(),
                            'info' => 'شراء منتج ' . $product->name,
                            'debit' => $product->getPrice(),
                            'credit' => 0,
                            'total' => auth()->user()->balance - $product->getPrice(),
                            'bill_id'=>$bill->id,
                        ]);

                        $bill = $service->buyFromApiFixed($bill);
                        $bill->save();
                        \DB::commit();
                        return HelperSupport::sendData(['bill' => new BillResource($bill), 'user' => new UserResource(auth()->user())]);

                    } catch (\Exception $e) {
                        \DB::rollBack();
                        throw new \Exception('حدث خطأ أثناء عملية الشراء ' . $e->getMessage());
                    }

                } else {
                    throw new \Exception('المنتج غير متوفر حاليا', 'المنتج غير متوفر حاليا');
                }


            }
            /*
             * Check Demo Account
             * */

            /*
             * Check any Error
             * */
            if ($request->id_user == '') {
                throw new \Exception('يرجى إضافة ID  الحساب');
            }

            $bill = new Bill();
            if ($product->is_free) {
                if ($request->amount < $product->min_amount) {
                    throw new \Exception('لا يمكن طلب كمية أقل من ' . $product->min_amount);
                }
                if ($request->amount > $product->max_amount) {
                    throw new \Exception('لا يمكن طلب كمية أكبر من ' . $product->max_amount);
                }
                $total_cost = ($product->total_cost / $product->amount) * $request->amount;
                $total_price = ($product->getPrice() / $product->amount) * $request->amount;
                $ratio = 0;
                $bill->amount = $request->amount;
            }//
            else {
                $total_cost = $product->total_cost;
                $total_price = $product->getPrice();
                $ratio = 0;
                $bill->amount = $product->count;

            }
            if (!$product->is_offer && auth()->user()->user != null) {
                $ratio = ($total_cost * auth()->user()->group->ratio_delegate);
                $bill->ratio = $ratio;
            }//
            elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                $ratio = ($total_cost * getSettings('affiliate_ratio'));
                $bill->ratio = $ratio;
            }
            if (auth()->user()->getTotalBalance() < $total_price) {
                throw new \Exception('لا تملك رصيد كافي للشراء');

            }//
            $invoice = Invoice::create([
                'status' => BillStatusEnum::PENDING->value,
                'user_id' => auth()->id(),
                'code' => Str::random(6),
                'total' => $total_price,
            ]);
            $bill->id_bill = Str::uuid()->toString();
            $bill->price = $total_price;
            $bill->cost = $total_cost;
            $bill->user_id = auth()->id();
            $bill->status = BillStatusEnum::PENDING->value;
            $bill->category_id = $product->category_id;
            $bill->product_id = $product->id;
            $bill->customer_note = $request->info;
            $bill->invoice_id = $invoice->id;
            ########################
            $black = Black::where('data', trim($request->id_user))->first();
            if ($black) {
                $bill->black_id = $black->id;
            }
            if ($product->type == ProductTypeEnum::NEED_ID) {

                $bill->customer_id = $request->id_user;
                $bill->customer_name = $request->name;


            }//
            elseif ($product->type == ProductTypeEnum::NEED_ACCOUNT) {
                $bill->customer_username = $request->id_user;
                $bill->customer_password = $request->name;


            }
            #########################3
            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $total_price,
                'credit' => 0,
                'info' => 'شراء منتج ' . $bill->amount . ' ' . $product->name,
                'total' => auth()->user()->balance - $total_price,
                'bill_id'=>$bill->id,
            ]);

            if ($product->is_free && $product->is_active_api && !$black) {

                if ($product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
                } elseif ($product->api == 'drd3') {
                    $service = new Drd3(getSettingsModel());
                }//
                elseif ($product->api == 'saud') {
                    $service = new SaudCard(getSettingsModel());
                }//
                elseif ($product->api == 'eko') {
                    $service = new EkoCard(getSettingsModel());
                } elseif ($product->api == 'as7ab') {
                    $service = new As7ab(getSettingsModel());
                }elseif ($product->api == 'mazaya') {
                    $service = new Mazaya(getSettingsModel());
                }elseif ($product->api == 'cache-back') {
                    $service = new CachBack(getSettingsModel());
                }
                $bill = $service->buyFromApiFree($bill);

            } //
            elseif (!$product->is_free && $product->is_active_api && !$black) {

                if ($product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
                }//
                elseif ($product->api == 'drd3') {
                    $service = new Drd3(getSettingsModel());
                }//
                elseif ($product->api == 'saud') {
                    $service = new SaudCard(getSettingsModel());
                } elseif ($product->api == 'eko') {
                    $service = new EkoCard(getSettingsModel());
                } elseif ($product->api == 'as7ab') {
                    $service = new As7ab(getSettingsModel());
                }elseif ($product->api == 'mazaya') {
                    $service = new Mazaya(getSettingsModel());
                }elseif ($product->api == 'cache-back') {
                    $service = new CachBack(getSettingsModel());
                }
                $bill = $service->buyFromApiFixed($bill);
            }//
            try {
                $bill->save();
            } catch (\Exception | \Error $e) {

            }
            return HelperSupport::sendData([
                'bill' => new BillResource($bill),
                'user' => new UserResource(auth()->user())
            ]);

        } //
        catch (\Exception | \Error $e) {
            HelperSupport::SendError(['msg' => $e->getMessage()]);
        }

    }

    public function checkPlayer(Request $request)
    {
        if (Balance::where('user_id', auth()->id())->count() === 0) {
            HelperSupport::SendError(['msg' => 'يجب شحن الرصيد قبل التحقق من اسم اللاعب']);
        }
        $product_id = $request->product_id;
        $game = Product::find($product_id)?->category?->game;
        $player_id = $request->player_id;
        $as7ab = new As7ab(getSettingsModel());
        try {
            $msg = $as7ab->getPlayerName($player_id, $game);
            return HelperSupport::sendData(['name' => $msg]);
        } catch (\Exception $e) {
            HelperSupport::SendError(['msg' => $e->getMessage()]);
        }
    }

    public function checkPlayerApi(Request $request)
    {
        if (Balance::where('user_id', auth()->id())->count() === 0) {
            HelperSupport::SendError(['msg' => 'يجب شحن الرصيد قبل التحقق من اسم اللاعب']);
        }
        if (now()->greaterThan(auth()->user()->expired_date) || !auth()->user()->is_check_name) {
            HelperSupport::SendError(['msg' => 'لا يمكن الفحص حاليا يرجى المحاولة لاحقا']);
        }
        $product_id = $request->product_id;
        $game = Product::find($product_id)?->category?->game;
        $player_id = $request->player_id;
        $as7ab = new As7ab(getSettingsModel());
        try {
            $msg = $as7ab->getPlayerName($player_id, $game);
            return HelperSupport::sendData(['name' => $msg]);
        } catch (\Exception $e) {
            HelperSupport::SendError(['msg' => $e->getMessage()]);
        }
    }
}
