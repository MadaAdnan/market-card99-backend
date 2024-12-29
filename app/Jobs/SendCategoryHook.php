<?php

namespace App\Jobs;

use App\Models\Category;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendCategoryHook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Category $category;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Category $category)
    {
        //
        $this->category = $category;
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
                    'model'=>'category',
                    'name' => $this->category->name,
                    'id' => $this->category->id,
                    'is_available' => (bool)$this->category?->is_available && (bool) $this->category?->active ,
                    'category_id'=>$this->category->category_id,
                ]);
            } catch (\Exception $exception) {
                info('Error Hook Category');
                info($exception->getMessage());
                info($user->hook_api);
            }
        }
    }
}
