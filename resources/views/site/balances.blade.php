@extends('site.layouts.master')


@section('content')

    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
        <div class="rounded bg-primary flex flex-col border border-primary text-white font-bold  justify-content-center items-center py-3">
            <p>المشتريات</p>
            <p>{{number_format(auth()->user()->debit,2)}}</p>
        </div>
        <div class="rounded bg-secondary flex flex-col border border-primary  text-white font-bold justify-content-center items-center py-3">
            <p>الرصيد الحالي</p>
            <p>{{number_format(auth()->user()->balance,2)}}</p>
        </div>
        <div class="rounded bg-primary flex flex-col border border-primary  text-white font-bold justify-content-center items-center py-3">
            <p>الارباح</p>
            <p>
              {{number_format(auth()->user()->getTotalPoint(),2)}}
            </p>
@if(auth()->user()->getTotalPoint()>5)
            <a class="bg-secondary rounded px-2 py-1" href="{{route('convert')}}">تحويل إلى رصيد</a>
    @endif
        </div>

        <div class="rounded bg-gray-300 flex flex-col border border-primary  text-white font-bold justify-content-center items-center py-3">
            <p>المسجلين من رابط الإحالة </p>
            <p>
                {{\App\Models\User::where('affiliate_id',auth()->id())->count()}}
            </p>
        </div>
    </div>
<div class="" >
    <div class="grid grid-cols-4 gap-2 m-auto">
        <div class="flex flex-col w-full col-span-4 ">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden w-full">
                        <table class="min-w-full border text-center">
                            <thead class="border-b">
                            <tr class="bg-secondary">
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                    ID
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                    مقبوضات
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                    مدفوعات
                                </th>

                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                    الإجمالي
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                    ملاحظات
                                </th>
                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                    التاريخ
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($balances as $balance)

                                <tr class=" @if($loop->iteration%2==0) bg-white  @endif border-b">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">{{$balance->id}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{number_format($balance->credit,2)}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{number_format($balance->debit,2)}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{number_format($balance->total,2)}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$balance->info}}</td>
                                    <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$balance->created_at->format('Y-m-d h:i a')}}</td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        {{$balances->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
