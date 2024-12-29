<div class="block w-full m-auto py-6 px-5 rounded-lg shadow-lg bg-gray-100  mt-[70px]">

    <div class="form-group mb-6">
        <label for="" class="form-label inline-block mb-2 text-gray-700">الاسم</label>
        <input type="text" readonly value="{{auth()->user()->name}}" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-gray-200 bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id=""
               aria-describedby="emailHelp" placeholder="Enter email">

    </div>
    <div class="form-group mb-6">
        <label for="" class="form-label inline-block mb-2 text-gray-700">اسم المستخدم</label>
        <input type="text" readonly value="{{auth()->user()->username}}" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-gray-200 bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id=""
               aria-describedby="emailHelp" placeholder="Enter email">

    </div>



    <div class="form-group mb-6">
        <label for="exampleInputEmail1" class="form-label inline-block mb-2 text-gray-700">العنوان</label>
        <input type="text" readonly value="{{auth()->user()->address}}" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-gray-200 bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputEmail1"
               aria-describedby="emailHelp" placeholder="قم بتوليد api  جديد">

    </div>

    <div class="form-group mb-6">
        <label for="exampleInputEmail1" class="form-label inline-block mb-2 text-gray-700">رقم الهاتف</label>
        <input type="text"  wire:model.lazy="phone" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
         bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputEmail1"
               aria-describedby="emailHelp" >
        @error('phone')
        <span class="text-small text-red-500">{{$message}} </span>
        @enderror
    </div>
@if(auth()->user()->email!='market@gmail.com')
        <div class="form-group mb-6">
            <label for="exampleInputEmail1" class="form-label inline-block mb-2 text-gray-700">البريد
                الإلكتروني</label>
            <input type="email"  wire:model.lazy="email" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
         bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputEmail1"
                   aria-describedby="emailHelp" >
            @error('email')
            <span class="text-small text-red-500">{{$message}} </span>
            @enderror
        </div>

        <div class="form-group mb-6">
            <label for="exampleInputEmail1" class="form-label inline-block mb-2 text-gray-700">كلمة المرور</label>
            <input type="text" wire:model.lazy="password" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
         bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputPassword"
                   aria-describedby="emailHelp" >
            @error('password')
            <span class="text-small text-red-500">{{$message}} </span>
            @enderror
            <span class="text-small text-green-500">اترك الحقل فارغ إذا كنت لا تريد تغيير كلمة المرور</span>

        </div>
        <div class="form-group mb-6">
            <label for="exampleInputEmail1" class="form-label inline-block mb-2 text-gray-700">Api Token</label>
            <input type="text" value="{{auth()->user()->token}}" class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
         bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputPassword"
                   aria-describedby="emailHelp" >

            <span class="text-small text-green-500">لا تعط الكود لأحد Api</span>

        </div>
@endif

    @if(auth()->user()->is_affiliate)
        <label for="">رابط الإحالة</label>
        <div class="flex items-center">
            <!-- Input field with copy button -->
            <div class="relative">
                <button onclick="copyToClipboard()" class="absolute inset-y-0 left-0 bg-blue-500 text-white p-2 rounded-l focus:outline-none hover:bg-blue-600">
                    Copy
                </button>
                <input type="text" readonly value="{{route('register',['affiliate'=>auth()->user()->affiliate])}}" id="myInput" class="w-64 border border-gray-300 p-2 rounded-r focus:outline-none focus:border-blue-500">

            </div>
        </div>


    @endif

    <button type="button" class="
      px-6
      py-2.5
      bg-blue-600
      text-white
      font-medium
      text-xs
      leading-tight
      uppercase
      rounded
      shadow-md
      hover:bg-blue-700 hover:shadow-lg
      focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0
      active:bg-blue-800 active:shadow-lg
      transition
      duration-150
      ease-in-out" wire:click="submit">حفظ
    </button>

</div>
<script>
    function copyToClipboard() {
        /* Get the text field */
        var input = document.getElementById("myInput");

        /* Select the text field */
        input.select();
        input.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        Swal.fire({
            title:'تم النسخ',
            position:'topLeft',
            confirmButton: false,
            duration:700
        })
    }
</script>
