<x-filament::page>
    <input type="text" wire:model="search" style="color: black" placeholder="بحث">
   <div class="w-full">
       @foreach($type as $key=>$value)
           <button class="bg-gray-500 mx-2 p-3 rounded  " wire:key="{{$key}}" @if(isset($site) &&  $site==$key)   style="background-color: #ff9900;color: #fff" @endif wire:click="getPrinceProduct('{{$key}}')"> {{$value}}</button>

       @endforeach
      {{-- <button class="bg-gray-500 mx-2 p-3 rounded  " @if(isset($site) &&  $site=='speed-card')  style="background-color: #ff9900;color: #fff" @endif wire:click="getPrinceProduct('speed-card')">تطبيقات Speed Card</button>
       <button class="bg-gray-500 mx-2 p-3 rounded  " @if(isset($site) &&  $site=='eko') style="background-color: #ff9900;color: #fff" @endif wire:click="getPrinceProduct('eko')">تطبيقات Eko </button>
       <button class="bg-gray-500 mx-2 p-3 rounded  " @if(isset($site) &&  $site=='drd3') style="background-color: #ff9900;color: #fff" @endif wire:click="getPrinceProduct('drd3')">تطبيقات Drd3 </button>
       <button class="bg-gray-500 mx-2 p-3 rounded  " @if(isset($site) &&  $site=='cash-mm') style="background-color: #ff9900;color: #fff" @endif wire:click="getPrinceProduct('cash-mm')">تطبيقات CashSmm </button>
  --}} </div>
    <hr>
    <h3 class="bg-gray-500 rounded p-3 " wire:loading="getPrinceProduct">جاري جلب البيانات ...</h3>
    @if(isset($type[$site]))
        <h1>{{$type[$site]}}</h1>
        @endif

    @if($site!='drd3' && $site!='cash-mm')
        @foreach($items as $item)
            <x-filament::card class="flex">
                <div>التطبيق : {{$item['name']??''}}</div>
                <div>السعر : {{$item['price']??''}}</div>
                <div>الكود : {{$item['id']??''}}</div>
            </x-filament::card>
        @endforeach
    @else
        @foreach($items as $item)
            <x-filament::card class="flex">
                <div>التطبيق : {{$item['name']}}</div>
                <div>السعر : 0</div>
                <div>الكود : {{$item['service']}}</div>
            </x-filament::card>
        @endforeach
    @endif

</x-filament::page>
