<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api2\BuyNumberRequest;
use App\Http\Resources\Api2\CountryResource;
use App\Http\Resources\Api2\OrderNumberResource;
use App\Http\Resources\Api2\ProgramResource;
use App\Http\Resources\Api2\ServerResource;
use App\Http\Resources\Api2\UserResource;
use App\InterFaces\ServerInterface;
use App\Models\Server;
use App\Models\Setting;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class ServerController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('store');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = Server::active()->orderBy('sort')->get();
        return HelperSupport::sendData(['servers' => ServerResource::collection($servers)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            $setting = Setting::first();
            $price = $app->pivot->price;
            if (auth()->user()->hasRole('partner')) {
                $price = $app->pivot->price - ($app->pivot->price * $setting->discount_delegate_online);
            }
            if (auth()->user()->balance >= $price) {
                $order = $lib->getPhoneNumber($country, $app);
                return HelperSupport::sendData(['order' => new OrderNumberResource($order),
                    'user'=>new UserResource(auth()->user())]);
            } else {
                throw new \Exception('لا تملك رصيد كاف لإتمام العملية');
            }

        } catch (\Exception | \Error $e) {
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
        $countries = Server::findOrFail($id)->countries;
        $apps = Server::findOrFail($id)->programs;
        return HelperSupport::sendData(['countries' => CountryResource::collection($countries),
            'apps' => ProgramResource::collection($apps)]);
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
