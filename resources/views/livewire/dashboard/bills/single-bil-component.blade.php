<tr @if($bill->api_id!=null && $bill->status->value==\App\Enums\BillStatusEnum::PENDING->value) wire:poll.10000ms="check" @endif>
    <td>{{$bill->id}}</td>
    <td>{{$bill->user->name}}</td>
    <td>{{$bill->product->name}}</td>
    <td>@if(auth()->user()->email=='admin@admin.com')
            {{$bill->cost}}
        @endif</td>
    <td>{{$bill->price}}</td>
    <td>{{$bill->customer_id}}</td>
    <td>{{$bill->data_id}}</td>
    <td>{{$bill->customer_name??$bill->customer_username}}</td>

    <td>{{$bill->customer_password}}</td>
    <td>{{$bill->amount}}</td>
    <td>{{$bill->customer_note}}</td>
    <td
        class="text-white @if($bill->status==\App\Enums\BillStatusEnum::CANCEL) bg-black   @elseif($bill->status==\App\Enums\BillStatusEnum::COMPLETE) bg-success  @elseif($bill->status==\App\Enums\BillStatusEnum::PENDING) bg-info     @else  bg-danger   @endif"
    >{{$bill->status->status()}}</td>
    <td>{{$bill->created_at->format('Y-m-d h:i a')}}</td>
    <td>{{$bill->api_id}}</td>
    <td>{{$bill->api}}</td>
    <td>
        <div class="btn-group">
            @if($bill->status->value!='cancel' && $bill->api_id==null)
                <button class="btn btn-sm btn-success" wire:click="statusComplete">الموافقة على الطلب</button>
            @endif
                <button class="btn btn-sm btn-danger" wire:click="statusCancel">إلغاء </button>

        </div>
    </td>
</tr>
