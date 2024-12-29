<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/site2.css')}}">
    <!-- font awesome cdn  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900;1000&display=swap"
          rel="stylesheet">
    <title>Market-Card | Login</title>
    <style>
*{
    font-family: Cairo;
}
@keyframes rotate {
    100% {
        transform: rotate(1turn);
    }
}

@keyframes opacityChange {
    50% {
        opacity: 1;
    }
    100% {
        opacity: 1;
    }
}
.rainbow {
    position: relative;
    z-index: 0;
    overflow: hidden;
    /*padding: 5px;*/
}

.rainbow::before {
    content: '';
    position: absolute;
    z-index: -2;
    left: -50%;
    top: -50%;
    width: 200%;
    height: 200%;
    background-color: #fff;
    background-repeat: no-repeat;
    background-size: 50% 50%, 50% 50%;
    background-position: 0 0, 100% 0, 100% 100%, 0 100%;
    background-image: linear-gradient(#000, #fff);
    animation: rotate 4s linear infinite;
}

.rainbow::after {
    content: '';
    position: absolute;
    z-index: -1;
    left: 6px;
    top: 6px;
    width: calc(100% - 12px);
    height: calc(100% - 12px);
    background: white;
    border-radius: 5px;
    animation: opacityChange 3s infinite alternate;
}
        .radial-gradient {
            background-image: radial-gradient(circle at 0 0,#1d2c37 15%, #f3f3f3 45%, #1d2c37);
        }
    </style>
</head>


<body dir="rtl" class="">


<main class="bg-gradient-to-r from-primary to-secondary  flex items-center justify-center h-screen px-2">
    <div class="bg-white py-6 px-[15px] rounded-lg shadow-md md:w-1/3 w-full rainbow ">
        <div class="flex items-center justify-center mb-4">
            <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center">
                <img src="{{getSettingsModel()?->getImage()}}" alt="MarketCard" class="w-12 h-12">
            </div>
        </div>
        <h2 class="text-2xl font-semibold text-center mb-4">تسجيل الدخول</h2>
        <form method="post" action="{{route('login')}}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-primary" placeholder="example@example.com">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-primary" placeholder="********">
            </div>
            <div class="text-center">
                <button type="submit" class="w-full bg-primary text-white text-sm font-semibold py-2 rounded-lg focus:ring focus:ring-primary hover:bg-secondary">تسجيل الدخول</button>
            </div>
            <p class="text-center text-gray-600 p-2">
                <a class="underline" href="{{route('register')}}">إنشاء حساب جديد</a>
            </p>
        </form>
    </div>
</main>
</body>

</html>
