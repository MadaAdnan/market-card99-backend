<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\DepartmentResource;
use App\Http\Resources\Api2\OrderNumberResource;
use App\Http\Resources\Api2\ProgramResource;
use App\Http\Resources\Api2\UserResource;
use App\Http\Resources\Sync\CategoryResource;
use App\Http\Resources\Sync\ProductResource;
use App\InterFaces\ServerInterface;
use App\Models\Category;
use App\Models\Department;
use App\Models\Product;
use App\Models\Program;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::where('is_active', 1)->get();
        return HelperSupport::sendData(['departments' => DepartmentResource::collection($departments)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Department $section)
    {
        $programs = Program::where(['department_id' => $section->id, 'is_active' => true])->whereHas('server', fn($query) => $query->where('servers.is_active', 'active'))->orderBy('sortable')->get();
        return HelperSupport::sendData(['programs' => ProgramResource::collection($programs)]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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

    public function buyNumber(Program $program)
    {
        try {
            $server = $program->server;
            /** @var ServerInterface $lib */
            $lib = new $server->code;
            $app = $program;
            $price = $program->getTotalPrice();
            if (auth()->user()->balance >= $price) {
                $order = $lib->getPhoneNumber($app);
                return HelperSupport::sendData(['order' => new OrderNumberResource($order),
                    'user' => new UserResource(auth()->user())]);
            } else {
                throw new \Exception('لا تملك رصيد كاف لإتمام العملية');
            }

        } catch (\Exception | \Error $e) {
            HelperSupport::SendError(['msg' => $e->getMessage()]);
        }
    }

    public function syncData()
    {
        $categories = Category::whereNot('id', 49)->whereNull('category_id')->with(['categories' => fn($query) => $query->with('products'), 'products'])->get();
        return HelperSupport::sendData(['categories' => CategoryResource::collection($categories)]);
    }

    public function getProduct()
    {
        $products = Product::where('products.active', true)->get();
        return HelperSupport::sendData(['products' => ProductResource::collection($products)]);
    }

}
