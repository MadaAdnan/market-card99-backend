<div class="w-full">
    <x-filament::card.heading class="relative overflow-x-auto">

            <label class="flex flex-col" for="">
                بداية المدة
                <input type="date" class="rounded " style="color: black" wire:model="start">
            </label>
            <label class="flex flex-col mr-3" for="">
                نهاية المدة
                <input type="date" class="rounded " style="color: black" wire:model="end">
            </label>

    </x-filament::card.heading>
   <x-filament::card.heading>زبائن الشحن</x-filament::card.heading>
    <x-filament::card.index class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr class="border border-secondary p-2 bg-gray-500  ">
                <th scope="col" class="px-6 py-3 dark:text-white text-center" >الزبون</th>
                <th scope="col" class="px-6 py-3 dark:text-white text-center" >الشحن</th>
                <th scope="col" class="px-6 py-3 dark:text-white text-center" >السحب</th>
                <th scope="col" class="px-6 py-3 dark:text-white text-center" >منذ</th>
            </tr>
            </thead>
            <tbody >
            @foreach($balances as $balance)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th class="border border-secondary p-2 dark:text-white text-center">{{$balance->user->name}}</th>
                    <th class="border border-secondary p-2 dark:text-white text-center">{{$balance->credit}}</th>
                    <th class="border border-secondary p-2 dark:text-white text-center">{{$balance->debit}}</th>
                    <th class="border border-secondary p-2 dark:text-white text-center">{{$balance->created_at->diffForHumans()}}</th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </x-filament::card.index>




</div>
