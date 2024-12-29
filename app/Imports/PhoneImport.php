<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class PhoneImport implements ToModel
{
    public Product $product;
    public function __construct(Product $product)
    {
        $this->product=$product;
    }

    /**
    * @param Collection $collection
    */

    public function model(array $row)
    {
        if(isset($row[0]) && !empty($row[0])){
            Item::create([
                'product_id'=>$this->product->id,
                'active'=>1,
                'code'=>$row[0],
                'phone'=>isset($row[1]) ?$row[1]:'',
            ]);
        }

    }
}
