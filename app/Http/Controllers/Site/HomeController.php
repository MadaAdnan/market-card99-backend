<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        $categories = Category::activate()->whereNull('category_id')->orderBy('sort')->get();

        $notifications = collect([]);
        $notification = 0;
        if (auth()->check()) {
            $notifications = DatabaseNotification::whereNotifiableType(User::class)->whereNotifiableId(auth()->id())->whereNull('read_at')->where('data->admin', 1)->get();
            $notification = DatabaseNotification::whereNotifiableType(User::class)->whereNotifiableId(auth()->id())->whereNull('read_at')->where('data->admin', 1)->latest()->first();

        }
        return view('site.index', compact('sliders', 'categories', 'notifications', 'notification'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $sliders = Slider::all();
        $categories = Category::activate()->whereCategoryId($category->id)->orderBy('sort')->get();
        return view('site.categories', compact('categories', 'sliders'));
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

    public function signout()
    {
        auth()->logout();
        return back();
    }
}
