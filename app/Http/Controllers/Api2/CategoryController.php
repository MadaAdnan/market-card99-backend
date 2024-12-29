<?php

namespace App\Http\Controllers\Api2;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api2\CategoryResource;
use App\Http\Resources\Api2\SliderResource;
use App\Models\Category;
use App\Models\Slider;
use App\Support\HelperSupport;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories=Category::where('active',true)->whereNull('category_id')->orderBy('sort')->get();
      //  $sliders=Slider::get();
        return HelperSupport::sendData([
            'categories'=>CategoryResource::collection($categories),
            ]);
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
        $categories=Category::activate()->where('category_id',$id)->orderBy('sort')->get();
        return HelperSupport::sendData([
            'categories'=>CategoryResource::collection($categories),
        ]);
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
    public function getCategoryById($id)
    {
        $category=Category::activate()->find($id);
        if($category){
            return HelperSupport::sendData(['category'=>new CategoryResource($category)]);
        }
        HelperSupport::SendError(['msg'=>'لم يتم العثور على القسم']);
    }
}
