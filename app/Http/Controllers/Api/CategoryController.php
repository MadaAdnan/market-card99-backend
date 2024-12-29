<?php

namespace App\Http\Controllers\Api;

use App\Enums\CategoryTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper;
use App\Http\Resources\Api\CategoryResource;
use App\Http\Resources\Api\ProductResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories=Category::where(['active'=>1])->whereHas('products')->get();
        return Helper::sendData(['categories'=>CategoryResource::collection($categories)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category=Category::where(['active'=>1,'type' => CategoryTypeEnum::DEFAULT->value])->with('products')->find($id);
        if(!$category){
            Helper::sendError('لم يتم العثور على القسم المطلوب');
        }
        return Helper::sendData(['products'=>ProductResource::collection($category->products)]);
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
