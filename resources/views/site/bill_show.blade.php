@extends('site.layouts.master')


@section('content')
    <div class="flex flex-col w-[100%]">
        <div class="overflow-x-auto w-full sm:-mx-6 lg:-mx-8">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden w-full">
                    <table class="min-w-full border text-center">
                        <thead class="border-b">
                        <tr class="bg-secondary">
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                #
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                               المنتج
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                                الإجمالي
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                ID / Phone
                            </th>

                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                اسم الحساب أو كلمة المرور
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                تاريخ الشراء
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                الكمية
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                 ملاحظات
                            </th>
                            <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                               البيانات
                            </th>


                                <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                                    سبب الإلغاء
                                </th>


                        </tr>
                        </thead>
                        <tbody>

                        @foreach($invoice->bills as $bill)

                            <tr class=" @if($loop->iteration%2==0) bg-white  @endif border-b">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">{{$bill->id_bill??$bill->id}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->product->name}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->total_price}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->customer_id}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->customer_name}} {{$bill->customer_password}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->created_at->format('Y-m-d ')}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->amount}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->customer_note}}</td>
                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->data_id}}  </td>

                                <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap border-r">{{$bill->cancel_note}}  </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
