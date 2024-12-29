<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>لوحة التحكم | MarketCard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
<meta  name="description" content="موقع يعطي أرقام لمنصات وتطبيقات التواصل الإجتماعي , أرقام واتس آب و أرقام تلغرام"/>
    <meta name="keywords" content="أرقام واتس آب رخيصة, تفعيل أرقام تلغرام, تفعيل أرقام واتس آب , أسعار منافسة , حسومات , تفعيل برامج وتطبيقات">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- bootstrap rtl -->
    <link rel="stylesheet" href="{{asset('dist/css/bootstrap-rtl.min.css')}}">
    <!-- template rtl version -->
    <link rel="stylesheet" href="{{asset('dist/css/custom-style.css')}}">
    @livewireStyles
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('css')
</head>
<body class="hold-transition sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>


        </ul>
        <!-- Right navbar links -->
        <ul class="navbar-nav mr-auto">
@hasrole('super_admin')
            <livewire:admin.notifications.bell-notification-component />

        @endhasrole
            <!-- Notifications Dropdown Menu -->
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index3.html" class="brand-link">
            <img src="{{asset('dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">لوحة التحكم</span>
        </a>

        <!-- Sidebar -->
@include('dashboard.layouts.sidebar')
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                            @yield('bread')
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
        @yield('content')
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <strong>جميع الحقوق محفوظة {{now()->format('Y')}} <a href="https://mada-company.com">شركة مدى للبرمجة والإنتاج المرئي</a>.</strong>
    </footer>

</div>
<!-- ./wrapper -->
@livewireScripts
<script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.min.js')}}"></script>
<script src="{{asset('dist/js/plugins/chartjs2/Chart.min.js')}}"></script>

@stack('js')

<script>
   window.addEventListener('success',(event)=>{
       Swal.fire({
           icon: 'success',
           title: 'تمت العملية بنجاح',
           text: event.detail.msg,
       })
   });
   window.addEventListener('error',(event)=>{
       Swal.fire({
           icon: 'error',
           title: 'حدث خطأ في العملية',
           text: event.detail.msg,
       })
   })



window.addEventListener('test',(event)=>{
    var audio = new Audio('{{asset('notify.mp3')}}');
    audio.play();

})
   window.addEventListener('deleteData',(event)=>{
       Swal.fire({
           title: 'هل أنت متأكد من الجذف',
           text: "لن تستطيع التراجع",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'نعم متأكد',
           cancelButtonText:'لغاء الأمر'
       }).then((result) => {
           if (result.isConfirmed) {
              window.livewire.emit('confirmDelete',{id:event.detail.id,model:event.detail.model});
           }
       })
   })
</script>
</body>
</html>
