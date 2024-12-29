<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/style.css?v=2.2')}}">
    <!-- font awesome cdn  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <!-- swiper cdn  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <!--  -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900;1000&display=swap"
          rel="stylesheet">
    <!-- alpine js cdn  -->
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    <!--  -->
    <link rel="icon" href="{{getSettingsModel()->getImage()}}" sizes="192x192">
    <link rel="shortcut icon" href="{{getSettingsModel()->getImage()}}" sizes="192x192">
    @auth
        <meta content="{{auth()->id()}}" name="AuthId">
    @endauth

    @guest
        <meta content="0" name="AuthId">
    @endguest

    <title>Market-Card</title>


    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" defer></script>
    <script src="{{asset('worker.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script>

        window.OneSignal = window.OneSignal || [];
        OneSignal.push(function () {
            OneSignal.init({
                appId: "122b8c3e-092c-4e6f-b51c-267936e81f5b",
            });
        });

        OneSignal.push(function () {
            if (localStorage.getItem('os-user') === null || localStorage.getItem('os-user') === 'null') {
                OneSignal.getUserId(function (token) {
                    localStorage.setItem('os-user', token);
                    $(function () {
                        var userId = $('meta[name="AuthId"]').attr('content');
                        var url = `{{url('api/registerToken/')}}/${userId}?token=${token}`;
                        console.log(url)
                        if (Notification.permission === 'granted') {
                            const xhr = new XMLHttpRequest();
                            xhr.open("GET", url);
                            xhr.send();
                            xhr.responseType = "json";
                            xhr.onload = () => {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    const data = xhr.response;
                                    console.log(data);
                                } else {
                                    console.log(`Error: ${xhr.status}`);
                                }
                            };
                        } else {
                            // User has not yet granted permission to receive notifications
                        }

                    })
                });
            } else {
                console.log('Test');
                console.log(localStorage.getItem('os-user'));
            }
        });


    </script>
    @livewireStyles

<!-- import font  -->
    @livewireStyles
    <style>
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

        .text-12 {
            font-size: 12px;
        }

        .rainbow {
            position: relative;
            z-index: 0;


            overflow: hidden;
            padding: 5px;
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
            background-image: linear-gradient(#FFBF5B, #FFBF5B), linear-gradient(#FFF, #FFF), linear-gradient(#FFF, #FFF), linear-gradient(#FFF, #FFF);
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

        @font-face {
            font-family: font-app;
            src: url("{{asset('assets/fonts/alfont_com_29LTZaridSlab-Regular.ttf')}}");
        }

        body {
            font-family: Cairo;
        }

        table * {
            font-weight: bold !important;

        }
    </style>
    <!--  -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('css')

</head>

<body dir="rtl" class="pb-[155px]">




<div class="w-full bg-primary h-[75px] fixed top-[0px] z-[10000000]" x-data="{ open: false }">
    @auth
        <div class="flex place-content-center items-center ">
            <a href="{{route('levels.index')}}"><img class=" max-h-[60px] rounded-md w-auto mt-2  " src="{{auth()->user()->group->getImage()}}"
                 alt="{{auth()->user()->group->name}}"></a>
        </div>
    @endauth

    <div class=" ">
        <button @click="open = !open" class="fixed top-4 right-4 p-2 bg-primary text-white z-50">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                 class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        @auth
            <span class="fixed top-4 left-4 p-2 bg-primary text-white z-50">
             {{auth()->user()->balance}} $
        </span>
        @endauth
    </div>

    <div x-show="open" @click.away="open = false"
         class="fixed top-0 overflow-scroll right-0 h-full bg-primary w-64 text-white p-4 transform translate-x-0 transition-transform duration-300 z-50">
        <ul class="space-y-4 pt-[50px] ">
            {{--            @hasanyrole('partner|super_admin')--}}

            @auth
                <li class="hover:bg-secondary p-1 mb-4 rounded">
                    <a href="" class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#fff"
                             class="bi bi-person-circle" viewBox="0 0 16 16">
                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                            <path fill-rule="evenodd"
                                  d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                        </svg>
                        &nbsp;&nbsp;<span>أهلا بك : <span>{{auth()->user()->name}}</span></span>
                    </a>
                </li>
                <li class="hover:bg-secondary p-1 mb-4 rounded">
                    <a href="{{route('notifications.index')}}" class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-bell-fill" viewBox="0 0 16 16">
                            <path
                                d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                        </svg>
                        &nbsp;&nbsp;<span>الإشعارات</span>
                    </a>
                </li>
                <li class="bg-red-700 p-1 mb-4 rounded">
                    <a href="https://documenter.getpostman.com/view/8817764/2s93JxqLin#f8353e63-c8a8-4781-984a-17a825366a11" class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-pdf" viewBox="0 0 16 16">
                            <path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1"/>
                            <path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/>
                        </svg>
                        &nbsp;&nbsp;<span>وثائق Api</span>
                    </a>
                </li>
                <li class="border-bottom border-[1px]"></li>
            @endauth

            @if(auth()->check() && (auth()->user()->hasRole('partner') ||auth()->user()->hasRole('super_admin')))


                <li class="hover:bg-secondary p-1 rounded">
                    <a href="{{url('/admin')}}" class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-speedometer" viewBox="0 0 16 16">
                            <path
                                d="M8 2a.5.5 0 0 1 .5.5V4a.5.5 0 0 1-1 0V2.5A.5.5 0 0 1 8 2zM3.732 3.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707zM2 8a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 8zm9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5zm.754-4.246a.389.389 0 0 0-.527-.02L7.547 7.31A.91.91 0 1 0 8.85 8.569l3.434-4.297a.389.389 0 0 0-.029-.518z"/>
                            <path fill-rule="evenodd"
                                  d="M6.664 15.889A8 8 0 1 1 9.336.11a8 8 0 0 1-2.672 15.78zm-4.665-4.283A11.945 11.945 0 0 1 8 10c2.186 0 4.236.585 6.001 1.606a7 7 0 1 0-12.002 0z"/>
                        </svg>&nbsp;&nbsp;<span>لوحة التحكم</span>
                    </a>
                </li>

            @endif
            {{--            @endhasanyrole--}}
            <li class="hover:bg-secondary p-1 rounded">
                <a href="{{route('home.index')}}" class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                         class="bi bi-house-door" viewBox="0 0 16 16">
                        <path
                            d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z"/>
                    </svg>&nbsp;&nbsp;<span>الرئيسية</span>
                </a>
            </li>
            @auth

                <li class="hover:bg-secondary p-1 rounded"><a href="{{route('profile.index')}}" class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                            <path
                                d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                        </svg>&nbsp;&nbsp;
                        <span>الملف الشخصي</span>
                    </a></li>
                <li class="hover:bg-secondary p-1 rounded">
                    <a href="{{route('charges.index')}}" class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-cash-coin" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                  d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z"/>
                            <path
                                d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z"/>
                            <path
                                d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z"/>
                            <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z"/>
                        </svg>&nbsp;&nbsp; <span>شحن الرصيد</span></a>
                </li>
                <li class="hover:bg-secondary p-1 rounded"><a href="{{route('coupons.index')}}" class="flex"> &nbsp;
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-credit-card-2-back-fill" viewBox="0 0 16 16">
                            <path
                                d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v5H0V4zm11.5 1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zM0 11v1a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-1H0z"/>
                        </svg>&nbsp;&nbsp; <span> كوبونات الشحن</span></a></li>
                <li class="hover:bg-secondary p-1 rounded"><a href="{{route('invoices.index')}}" class="flex"> &nbsp;<svg
                            xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-cart"
                            viewBox="0 0 16 16">
                            <path
                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>&nbsp;&nbsp; <span>مشتريات مختلفة</span></a></li>
                <li class="hover:bg-secondary p-1 rounded"><a href="{{ route('online.orders') }}" class="flex"> &nbsp;<svg
                            xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-wifi"
                            viewBox="0 0 16 16">
                            <path
                                d="M15.384 6.115a.485.485 0 0 0-.047-.736A12.444 12.444 0 0 0 8 3C5.259 3 2.723 3.882.663 5.379a.485.485 0 0 0-.048.736.518.518 0 0 0 .668.05A11.448 11.448 0 0 1 8 4c2.507 0 4.827.802 6.716 2.164.205.148.49.13.668-.049z"/>
                            <path
                                d="M13.229 8.271a.482.482 0 0 0-.063-.745A9.455 9.455 0 0 0 8 6c-1.905 0-3.68.56-5.166 1.526a.48.48 0 0 0-.063.745.525.525 0 0 0 .652.065A8.46 8.46 0 0 1 8 7a8.46 8.46 0 0 1 4.576 1.336c.206.132.48.108.653-.065zm-2.183 2.183c.226-.226.185-.605-.1-.75A6.473 6.473 0 0 0 8 9c-1.06 0-2.062.254-2.946.704-.285.145-.326.524-.1.75l.015.015c.16.16.407.19.611.09A5.478 5.478 0 0 1 8 10c.868 0 1.69.201 2.42.56.203.1.45.07.61-.091l.016-.015zM9.06 12.44c.196-.196.198-.52-.04-.66A1.99 1.99 0 0 0 8 11.5a1.99 1.99 0 0 0-1.02.28c-.238.14-.236.464-.04.66l.706.706a.5.5 0 0 0 .707 0l.707-.707z"/>
                        </svg> &nbsp;&nbsp;<span>مشتريات الأرقام</span></a></li>
                <li class="hover:bg-secondary p-1 rounded"><a href="{{route('balances.index')}}" class="flex"> &nbsp;<svg
                            xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                            class="bi bi-coin"
                            viewBox="0 0 16 16">
                            <path
                                d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z"/>
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path
                                d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"/>
                        </svg> &nbsp;&nbsp;<span>حركة الرصيد</span></a></li>
                <li class="hover:bg-secondary p-1 rounded"><a href="{{route('points.index')}}" class="flex"> &nbsp;
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M0 0h1v15h15v1H0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5"/>
                        </svg>
                        &nbsp;&nbsp;<span>حركة الأرباح</span></a></li>

                <li class="hover:bg-secondary p-1 rounded"><a href="{{route('asks.index')}}" class="flex"> &nbsp;
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-question-octagon" viewBox="0 0 16 16">
                            <path
                                d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353L4.54.146zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1H5.1z"/>
                            <path
                                d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z"/>
                        </svg>&nbsp;&nbsp;
                        <span>طلب وكالة</span></a></li>
                <li class="bg-red-700 p-1 rounded"><a href="{{route('sign-out')}}" class="flex"> &nbsp;
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                             class="bi bi-door-open" viewBox="0 0 16 16">
                            <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                            <path
                                d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117zM11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5zM4 1.934V15h6V1.077l-6 .857z"/>
                        </svg>&nbsp;&nbsp;
                        <span>خروج</span></a></li>
                {{--            <li><a href="#" class="flex"> &nbsp; <span></span></a></li>--}}
            @endauth
            <li class=" p-1 rounded">
                <div class="flex flex-row border border-dashed p-2">
                    @if(isset(getSettings('social')['whatsapp'])&&!empty(getSettings('social')['whatsapp']))
                        <a
                            href="https://wa.me/{{ltrim(ltrim(getSettings('social')['whatsapp'],'00'),'+')}}"
                            class="flex hover:bg-secondary px-1 py-1 rounded"> &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#00FF00"
                                 class="bi bi-whatsapp" viewBox="0 0 16 16">
                                <path
                                    d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                            </svg>{{--&nbsp;&nbsp;<span>واتسآب</span>--}}
                        </a>
                    @endif
                    @if(isset(getSettings('social')['telegram'])&&!empty(getSettings('social')['telegram']))
                        <div class="border-right border-gray-500 border-[1px]"></div>
                            <a href="{{getSettings('social')['telegram']}}"
                           class="flex hover:bg-secondary px-2 py-1 rounded"> &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#0077FF"
                                 class="bi bi-telegram" viewBox="0 0 16 16">
                                <path
                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                            </svg>{{--&nbsp;&nbsp;<span>تلغرام</span>--}}
                        </a>
                    @endif

                    @if(isset(getSettings('social')['facebook'])&&!empty(getSettings('social')['facebook']))
                            <div class="border-right border-gray-500 border-[1px]"></div>
                        <a href="{{getSettings('social')['facebook']}}"
                           class="flex hover:bg-secondary px-2 py-1 rounded"> &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#0000F8"
                                 class="bi bi-facebook" viewBox="0 0 16 16">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                            </svg>{{--&nbsp;&nbsp;<span>فيس بوك</span>--}}
                        </a>
                    @endif
                    @if(isset(getSettings('social')['instagram'])&&!empty(getSettings('social')['instagram']))
                            <div class="border-right border-gray-500 border-[1px]"></div>
                        <a href="{{getSettings('social')['instagram']}}"
                           class="flex hover:bg-secondary px-2 py-1 rounded"> &nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="#FF9960" class="bi bi-instagram" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334"/>
                            </svg>{{--&nbsp;&nbsp;<span>إنستغرام</span>--}}
                        </a>
                    @endif

                </div>
            </li>
        </ul>
    </div>
</div>
{{--NAV BAR--}}

{{--NAV BAR--}}
<main class="px-5  md:px-16  mx-auto lg:px-32 xl:px-48 relative top-[77px] bg-white shadow-md min-h-screen ">
    @yield('content')

</main>
<footer class="bg-[#000]">
    <div class="fixed bottom-0 left-0 w-full bg-gray-800 p-4">
        <div class="container-fluid mx-auto">
            <div class="m-auto">
                <ul class="flex  justify-between w-full md:w-[75%] m-auto">
                    <li class="mx-2   hover:bg-secondary rounded @if(request()->routeIs('home.index'))   text-white bg-secondary  rounded  @endif p-2">
                        <a href="{{ route('home.index') }}" class="text-white  flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                 class="bi bi-house" viewBox="0 0 16 16">
                                <path
                                    d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                            </svg>
                            @if(request()->routeIs('home.index'))
                                &nbsp;<span class="text-[10px]">الرئيسية</span>
                            @endif

                        </a>
                    </li>
                    @auth
                        <li class="mx-2  hover:bg-secondary rounded @if(request()->routeIs('online.index'))   text-white bg-secondary  rounded  @endif p-2">
                            <a href="{{ route('online.index') }}" class="text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                     class="bi bi-hdd-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M0 10a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-1zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zm2 0a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM.91 7.204A2.993 2.993 0 0 1 2 7h12c.384 0 .752.072 1.09.204l-1.867-3.422A1.5 1.5 0 0 0 11.906 3H4.094a1.5 1.5 0 0 0-1.317.782L.91 7.204z"/>
                                </svg>
                                @if(request()->routeIs('online.index'))
                                    &nbsp;<span class="text-[10px]">أونلاين</span>
                                @endif
                            </a>
                        </li>

                        <li class="mx-2  hover:bg-secondary rounded @if(request()->routeIs('invoices.index'))   text-white bg-secondary  rounded  @endif p-2">
                            <a href="{{route('invoices.index')}}" class="text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                     class="bi bi-receipt-cutoff" viewBox="0 0 16 16">
                                    <path
                                        d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zM11.5 4a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm0 2a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z"/>
                                    <path
                                        d="M2.354.646a.5.5 0 0 0-.801.13l-.5 1A.5.5 0 0 0 1 2v13H.5a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1H15V2a.5.5 0 0 0-.053-.224l-.5-1a.5.5 0 0 0-.8-.13L13 1.293l-.646-.647a.5.5 0 0 0-.708 0L11 1.293l-.646-.647a.5.5 0 0 0-.708 0L9 1.293 8.354.646a.5.5 0 0 0-.708 0L7 1.293 6.354.646a.5.5 0 0 0-.708 0L5 1.293 4.354.646a.5.5 0 0 0-.708 0L3 1.293 2.354.646zm-.217 1.198.51.51a.5.5 0 0 0 .707 0L4 1.707l.646.647a.5.5 0 0 0 .708 0L6 1.707l.646.647a.5.5 0 0 0 .708 0L8 1.707l.646.647a.5.5 0 0 0 .708 0L10 1.707l.646.647a.5.5 0 0 0 .708 0L12 1.707l.646.647a.5.5 0 0 0 .708 0l.509-.51.137.274V15H2V2.118l.137-.274z"/>
                                </svg>
                                @if(request()->routeIs('invoices.index'))
                                    &nbsp;<span class="text-[10px]">المشتريات</span>
                                @endif
                            </a>
                        </li>

                        <li class="mx-2  hover:bg-secondary rounded @if(request()->routeIs('online.orders'))   text-white bg-secondary  rounded  @endif p-2">
                            <a href="{{ route('online.orders') }}" class="text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                     class="bi bi-card-checklist" viewBox="0 0 16 16">
                                    <path
                                        d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                                    <path
                                        d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @if(request()->routeIs('online.orders'))
                                    &nbsp;<span class="text-[10px]">مشتريات الأرقام</span>
                                @endif
                            </a>
                        </li>
                    @else
                        <li class="mx-2   hover:bg-secondary rounded @if(request()->routeIs('login.ui'))   text-white bg-secondary  rounded  @endif p-2">
                            <a href="{{ route('login.ui') }}" class="text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                     class="bi bi-door-open-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2h.5a.5.5 0 0 1 .5.5V15h-1V2zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z"/>
                                </svg>
                                &nbsp;<span class="text-[10px]">تسجيل دخول</span>

                            </a>
                        </li>
                        <li class="mx-2   hover:bg-secondary rounded @if(request()->routeIs('register'))   text-white bg-secondary  rounded  @endif p-2">
                            <a href="{{ route('register') }}" class="text-white flex items-center">

                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                     class="bi bi-person-vcard" viewBox="0 0 16 16">
                                    <path
                                        d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5ZM9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8Zm1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5Z"/>
                                    <path
                                        d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2ZM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12V4Z"/>
                                </svg>
                                &nbsp;<span class="text-[10px]">حساب جديد</span>
                            </a>
                        </li>

                    @endauth
                </ul>
            </div>
        </div>
    </div>

</footer>
<!-- import this for canvas -->
<script src="{{asset('js/canvas.js')}}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@livewireScripts
@stack('js')
@if(session('error'))

    <script>
        Swal.fire({
            icon: 'error',
            title: 'فشل العملية',
            text: '{{Session::get('error')}}',
        })
    </script>
@endif

@if(session('success'))

    <script>
        Swal.fire({
            icon: 'success',
            title: 'نجاح العملية',
            text: '{{Session::get('success')}}',
        })
    </script>
@endif
<script>
    window.addEventListener('success', (event) => {
        Swal.fire({
            icon: 'success',
            title: 'تمت العملية بنجاح',
            text: event.detail.msg,
        })
    });
    window.addEventListener('error', (event) => {
        let url = '';
        let msg = event.detail.msg;
        if (event.detail.msg.includes("@")) {
            url = `<a href="https://wa.me/${event.detail.msg.split('@')[1]}">+${event.detail.msg.split('@')[1]}</a>`;
            msg = event.detail.msg.split('@')[0];
        }

        Swal.fire({
            icon: 'error',
            title: 'حدث خطأ في العملية',
            text: msg,
            footer: url
        })
    })

</script>
<script defer>


</script>


</body>

</html>
