<?php

namespace App\Http\Controllers\Api;

use App\Enums\BillStatusEnum;
use App\Enums\ProductTypeEnum;
use App\FromApi\As7ab;
use App\FromApi\CachBack;
use App\FromApi\EkoCard;
use App\FromApi\Juneed;
use App\FromApi\LifeCash;
use App\FromApi\Mazaya;
use App\FromApi\SaudCard;
use App\FromApi\SpeedCard;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Requests\Api\BillRequest;
use App\Http\Resources\Api\BillResource;
use App\Models\Balance;
use App\Models\Bill;
use App\Models\Invoice;
use App\Models\Point;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use mysql_xdevapi\Exception;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bills = Bill::where('user_id', auth()->id())->latest()->paginate(25);
        return Helper::sendData(['orders' => BillResource::collection($bills), 'next_page' => $bills->nextPageUrl()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BillRequest $request, $id)
    {

        $product = Product::where(['is_available' => 1, 'active' => 1])->find($id);
        if (!$product) {
            Helper::sendError('المنتج غير متوفر');
        }
        if (!$product->category->is_available || !$product->category->active) {
            Helper::sendError('المنتج غير متوفر');
        }
        $qty = $request->qty;
        $player_id = $request->player_id;
        $player_name = $request->player_name;
        ############## Items ###################


        if ($product->type->value == ProductTypeEnum::DEFAULT->value) {
            \DB::beginTransaction();
            try {
                if (auth()->user()->balance < $product->getPrice()) {
                    throw new \Exception('لا تملك رصيد كافي للشراء');
                }
                $items = $product->items()->whereNull('bill_id')->first();


                if (!$items) {
                    throw new \Exception('المنتج غير متوفر حاليا');
                }
                $invoice = Invoice::create([
                    'status' => BillStatusEnum::COMPLETE->value,
                    'code' => uniqid('M', true),
                    'total' => $product->getPrice(),
                    'user_id' => auth()->id()
                ]);
                $price = $product->getPrice();
                $ratio = 0;
                if (auth()->user()->user != null) {
                    $ratio = ($product->total_cost * auth()->user()->group->ratio_delegate);
                } elseif (auth()->user()->affiliate_user != null) {
                    $ratio = ($product->total_cost * getSettings('affiliate_ratio'));
                }
                $bill = Bill::create([
                    'id_bill' => Str::random(),
                    'price' => $price,
                    'ratio' => $ratio,
                    'status' => BillStatusEnum::COMPLETE->value,
                    'user_id' => auth()->id(),
                    'category_id' => $product->category_id,
                    'cost' => $product->total_cost,
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'data_id' => $items->code,
                ]);
                $items->update([
                    'bill_id' => $bill->id,
                    'active' => 0,
                ]);
                auth()->user()->balances()->create([
                    'total' => auth()->user()->balance - $price,
                    'info' => 'شراء كود من منتج ' . $product->name,
                    'debit' => $price,
                    'bill_id'=>$bill->id,

                ]);
                if ((auth()->user()->user != null || auth()->user()->affiliate_user != null) && !$product->is_offer) {
                    Point::create([
                        'user_id' => auth()->user()->user_id ?? auth()->user()->affiliate_id,
                        'credit' => $ratio,
                        'info' => 'رح من شراء ' . $product->name . ' الزبون : ' . auth()->user()->name,
                        'bill_id'=>$bill->id,
                    ]);
                }
                \DB::commit();
                return Helper::sendData(['order' => new BillResource($bill)]);

            } catch (\Exception $e) {
                \DB::rollBack();
                Helper::sendError($e->getMessage());
            }


        }


        ############## End Items ###########

        \DB::beginTransaction();
        try {
            if ($product->type != ProductTypeEnum::ITEMS->value && empty($player_id)) {
                throw new Exception('يرجى إدخال Id  أو Phone أو ...');
            }
            if ($product->is_free && ($request->qty < $product->min_amount || $request->qty > $product->max_amount)) {
                throw new Exception('يجب أن تكون الكمية المطلوبة بين ' . $product->min_amount . ' و ' . $product->max_amount);
            }
            $bill = new Bill();
            if ($product->is_free) {
                $total_cost = ($product->total_cost / $product->amount) * $qty;
                $total_price = ($product->getPrice() / $product->amount) * $qty;
                $ratio = 0;
                $bill->amount = $qty;
            }//
            else {
                $total_cost = $product->total_cost;
                $total_price = $product->getPrice();
                $ratio = 0;
                $bill->amount = 1;

            }
            if (!$product->is_offer && auth()->user()->user != null) {
                $ratio = ($total_cost * auth()->user()->group->ratio_delegate);
                $bill->ratio = $ratio;
            }//
            elseif (!$product->is_offer && auth()->user()->affiliate_user != null) {
                $ratio = ($total_cost * getSettings('affiliate_ratio'));

                $bill->ratio = $ratio;
            }
            //return auth()->user()->balance;
            if (auth()->user()->balance < $total_price) {
                throw new \Exception('لا تملك رصيد كافي للشراء');

            }//
            $invoice = Invoice::create([
                'status' => BillStatusEnum::PENDING->value,
                'user_id' => auth()->id(),
                'code' => Str::random(6),
                'total' => $total_price,
            ]);
            $bill->id_bill = Str::random();
            $bill->price = $total_price;
            $bill->cost = $total_cost;
            $bill->user_id = auth()->id();
            $bill->status = BillStatusEnum::PENDING->value;
            $bill->category_id = $product->category_id;
            $bill->product_id = $product->id;
            $bill->customer_note = $request->info;
            $bill->invoice_id = $invoice->id;
            $bill->customer_id = $player_id;
            $bill->customer_username = $player_id;
            $bill->customer_name = $player_name;
            ########################
            $data = [
                'price' => $total_price,
                'cost' => $total_cost,
                'user_id' => auth()->id(),
                'status' => BillStatusEnum::PENDING->value,
                'category_id' => $product->category_id,
                'product_id' => $product->id,
                'customer_note' => $request->info,
                'invoice_id' => $invoice->id,
            ];
            if ($product->type == ProductTypeEnum::NEED_ACCOUNT) {
                $bill->customer_password = $player_name;
            }
            $bill->save();
            #########################3
            Balance::create([
                'user_id' => auth()->id(),
                'debit' => $total_price,
                'credit' => 0,
                'info' => 'شراء منتج ' . $bill->amount . ' ' . $product->name,
                'total' => auth()->user()->balance - $total_price,
                'bill_id'=>$bill->id,
            ]);


//            $bill = Bill::create($data);
            if ($product->is_free && $product->is_active_api) {

                if ($product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
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
                }elseif ($product->api == 'juneed') {
                    $service = new Juneed(getSettingsModel());
                }
                $service->buyFromApiFree($bill);

            } //
                elseif (!$product->is_free && $product->is_active_api) {

                if ($product->api == 'life-cash') {
                    $service = new LifeCash(getSettingsModel());
                }//
                elseif ($product->api == 'speed-card') {
                    $service = new SpeedCard(getSettingsModel());
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
                }elseif ($product->api == 'juneed') {
                    $service = new Juneed(getSettingsModel());
                }
                $service->buyFromApiFixed($bill);
            }//
            $bill->save();
            \DB::commit();
            return Helper::sendData(['order' => new BillResource($bill)]);
        } catch (\Exception $e) {
            \DB::rollBack();
            Helper::sendError($e->getMessage());
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
        $bill = Bill::where('user_id', auth()->id())->where('id_bill', $id)->first();
        if (!$bill) {
            Helper::sendError('لم يتم العثور على الطلب');
        }
        return Helper::sendData(['order' => new BillResource($bill)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BillRequest $request, $id)
    {

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
}
