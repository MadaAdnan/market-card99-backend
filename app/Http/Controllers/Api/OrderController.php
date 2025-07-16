<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\OrderResource;
use App\InterFaces\ServerInterface;
use App\Models\Country;
use App\Models\Order;
use App\Models\Program;
use App\Models\Server;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $order_count = 0;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = auth()->user()->orders;
        return response()->json(['status' => 'success', 'orders' => OrderResource::collection($orders)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Server $server, Country $country, Program $program)
    {
        if ($server == null || $country == null || $program == null) {

            return response(['status' => 'error', 'msg' => 'يرجى تحديد السيرفر والدولة والتطبيق للشراء']);
        }
        try {
            /** @var ServerInterface $lib */
            $lib = new $server->code;
            $app = $server->programs()->findOrFail($program->id);
            if (auth()->user()->balance >= $app->pivot->price) {
                $order = $lib->getPhoneNumber($country, $app);
                return response()->json(['status' => 'success', 'order' => new OrderResource($order)]);
            } else {
                return response()->json(['status' => 'error', 'msg' => 'لا تملك رصيد كاف لإتمام العملية']);
            }
        } catch (\Exception | \Error $e) {
            $this->order_count++;
            if ($this->order_count < 3) {

                $this->store($server, $country, $program);
            }

            return response()->json(['status' => 'error', 'msg' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        if ($order->status == OrderStatusEnum::CANCEL->value || $order->status == OrderStatusEnum::COMPLETE->value|| !empty($order->code)) {
            return response()->json(['status' => 'success', 'order' => new OrderResource($order)]);
        }else{
            $server = $order->server;
            $lib = new $server->code;
            $status = $lib->getPhoneCode($order);
            try {
                if ($status == OrderStatusEnum::WAITE->value) {
                    return response()->json(['status' => 'wait', 'order' => new OrderResource($order)]);
                } elseif ($status == OrderStatusEnum::CANCEL->value) {
                    $new_order = Order::find($order->id);
                    return response()->json(['status' => 'cancel', 'order' => new OrderResource($new_order)]);
                } else {
                    $new_order = Order::find($order->id);
                    return response()->json(['status' => 'success', 'order' => new OrderResource($new_order)]);
                }
            } catch (\Exception | \Error $e) {
                return response()->json(['status' => 'error', 'order' => new OrderResource($order)]);
            }
        }



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
}
