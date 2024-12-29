<?php

namespace App\Http\Controllers\Api2;

use App\Enums\OrderStatusEnum;
use App\Helpers\Sms3t;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api2\BuyNumberRequest;
use App\Http\Resources\Api2\OrderNumberResource;
use App\Http\Resources\Api2\UserResource;
use App\InterFaces\ServerInterface;
use App\Models\Order;
use App\Models\Server;
use App\Models\Setting;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search=\request()->input('search');
        $orders=Order::where('user_id',auth()->id())
            ->where(fn($query)=>$query->where('phone','like','%'.$search.'%')->orWhere('code','like','%'.$search.'%'))->latest()->paginate(10);
        return HelperSupport::sendData(['orders'=>OrderNumberResource::collection($orders),'total_page'=>$orders->lastPage()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BuyNumberRequest $request)
    {

        try {
            $server = Server::active()->findOrFail($request->server_id);
            /** @var ServerInterface $lib */
            $lib = new $server->code;
            $app = $server->programs()->findOrFail($request->app_id);
            $country = $server->countries()->findOrFail($request->country_id);
            $setting=Setting::first();
            $price = $app->pivot->price;
            if (auth()->user()->hasRole('partner')) {
                $price = $app->pivot->price - ($app->pivot->price * $setting->discount_delegate_online);
            }
            if (auth()->user()->balance >= $price) {
                $order = $lib->getPhoneNumber($country, $app);
                return HelperSupport::sendData(['order'=>new OrderNumberResource($order)]);
            } else {
                throw new \Exception( 'لا تملك رصيد كاف لإتمام العملية');
            }

        } catch (\Exception | \Error $e) {
            HelperSupport::SendError(['msg' => $e->getMessage()]);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::find($id);
        /** @var ServerInterface $lib */
        $lib = new $order->server->code;
        if ($order->status->value == OrderStatusEnum::WAITE->value || $order->status->value=='waite') {
            $lib->getPhoneCode($order);
        }
        return HelperSupport::sendData(['order'=>new OrderNumberResource($order),
            'user'=>new UserResource(auth()->user())]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
