<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendHookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Product $product)
    {
        //
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $users = User::whereNotNull('hook_api')->where('is_active_hook', true)->get();
        foreach ($users as $user) {
            try {

                $response = \Http::withHeaders([
                    'Content-Type'=>'application/json',
                    'Accept'=>'text/json'
                ])->post($user->hook_api, [
                    '_method'=>'POST',
                    'model'=>'product',
                    'name' => $this->product->name,
                    'id' => $this->product->id,
                    'is_active' => (bool)$this->product->active && $this->product->category?->active,
                    'is_available' => (bool)$this->product->is_available && $this->product->category?->is_available,
                    'price' => $this->product->getPrice($user),
                    'type' => $this->product->type,
                    'is_free' => (bool)$this->product->is_free,
                    'min_qty' => $this->product->min_amount,
                    'max_qty' => $this->product->max_amount,
                    'info' => $this->product->info,
                    'category_id'=>$this->product->category_id,
                    'unit_price' => $this->product->getPrice($user) / ($this->product->amount > 0 ? $this->product->amount : 1),
                ]);
            } catch (\Exception $exception) {
                info('Error Hook');
                info($exception->getMessage());
                info($user->hook_api);
            }
        }
    }
}
