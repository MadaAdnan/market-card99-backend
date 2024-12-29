<?php

namespace App\Http\Controllers\Site;

use App\Enums\BillStatusEnum;
use App\Enums\CurrencyEnum;
use App\Enums\ProductTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Product;

use App\Notifications\SendNotificationDB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Category $category)
    {
        $products = $category->products()->activate()->orderBy('sort')->get();
        return view('site.products', compact('category', 'products'));
    }


    public function show(Product $product)
    {
        return view('site.products_show', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $this->validate($request, [
            'id' => 'required',

        ]);
        if (auth()->user()->balance < $product->total_price) {
            // \request()->session()->flash('error', 'test');
            return back()->with('error', 'لا تملك رصيد كافي');
        }
        \DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                'code' => uniqid('M', true),
                'status' => BillStatusEnum::PENDING,
                'total' => $product->total_price,
                'user_id'=>auth()->id(),
            ]);
            $cost=$product->cost;
            if($product->currency==CurrencyEnum::TR){
                $cost=$product->cost/getSettings('usd_price')??1;
            }
            $data = [

                'status' => BillStatusEnum::PENDING,
                'category_id' => $product->category_id,
                'cost' =>$cost ,
                'invoice_id' => $invoice->id,
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'customer_note'=>$request->info
            ];
            if (auth()->user()->user != null) {
                $data['price'] =$product->total_price;
                $data['ratio'] = $product->total_price-$product->price;
            } else {
                $data['price'] = $product->total_price;
            }

            if ($product->type == ProductTypeEnum::NEED_ID) {
                $data['customer_id'] = $request->id;
                $data['customer_name'] = $request->name;
            } elseif ($product->type == ProductTypeEnum::NEED_ACCOUNT) {
                $data['customer_username'] = $request->id;
                $data['customer_password'] = $request->name;
            }

            Bill::create($data);
            auth()->user()->balances()->create([
                'total' => auth()->user()->balance - $product->price,
                'debit' => $product->total_price,
                'info' => 'شراء ' . $product->name,
            ]);
            if (auth()->user()->user != null) {

                auth()->user()->user->balances()->create([
                    'total' => auth()->user()->user->balance + ($product->total_price-$product->price ),
                    'credit' => ($product->total_price-$product->price ),
                    'info' => 'أرباح من بيع ' . $product->name,
                    'ratio' => ($product->total_price-$product->price ),
                ]);
            }
            \DB::commit();
            return redirect()->route('invoices.index')->with('success', 'تم شراء المنتج وسيتم تنفيذ الطلب بأسرع وقت');
        } catch (\Exception|\Error) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ أثناء المعالجة يرجى المحاولة مرة أخرى');
        }

    }
}
