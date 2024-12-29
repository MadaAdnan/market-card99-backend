<tr class="
     text-white  border-b" @if($bill->status->value==\App\Enums\BillStatusEnum::PENDING->value && $bill->api_id!=null)  wire:poll.10000ms="check" @endif>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">{{$bill->id_bill??$bill->id}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->product->name}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->total_price}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4  whitespace-nowrap @if($bill->status->value=='pending') bg-blue-200  @elseif($bill->status->value=='cancel')  bg-red-700 @elseif($bill->status->value=='complete') bg-green-700 @endif border-r">{{$bill->status->status()}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->customer_id}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->customer_name}} {{$bill->customer_password}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->created_at->format('Y-m-d ')}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->amount}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->customer_note}}</td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->data_id}}  </td>

    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->cancel_note}}  </td>
    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">@if($bill->api_id==null && $bill->status->value==\App\Enums\BillStatusEnum::PENDING->value)
            <a href="{{route('orders.cancel',['invoice'=>$bill->id])}}" class="rounded bg-secondary text-white px-2 py-1">طلب إلغاء   </a>
        @endif</td>
</tr>
