<div class="flex flex-col w-[100%]" >
    <div class="overflow-x-auto w-full sm:-mx-6 lg:-mx-8">
        <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
            <input type="text"  wire:model="search" class="form-control block m-2
        w-1/3
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-white bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                   placeholder="بحث">
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
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 border-r">
                            الحالة
                        </th>
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                            ID / Phone
                        </th>

                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                            الحساب
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
                        <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4">
                            التحكم
                        </th>


                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bills as $bill)
                      <livewire:site.bills.single-bil-component :bill="$bill" :key="$bill->id"/>
                    @endforeach

                    </tbody>
                </table>
                {{$bills->links()}}
            </div>
        </div>
    </div>
</div>
