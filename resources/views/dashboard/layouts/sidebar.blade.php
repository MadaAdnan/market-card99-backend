<div class="sidebar">
    <div>
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="https://secure.gravatar.com/avatar/5ffa2a1ffeb767c60ab7e1052e385d5c?s=52&d=mm&r=g"
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{auth()->user()->name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{route('home.index')}}" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>عودة للموقع</p>
                    </a>
                </li>
                @hasrole('super_admin')
                <li class="nav-header">الإعدادات</li>
                <li class="nav-item">
                    <a href="{{route('dashboard.statistics.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.statistics.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الإحصائيات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard.asks.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.asks.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>أسئلة الوكالة</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.banks.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.banks.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>طرق التحويل</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.coupons.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.coupons.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الكوبونات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.charges.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.charges.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>طلبات الشحن</p>
                        {{--@php
                            $count=\App\Models\Recharge::where('status',\App\Enums\BillStatusEnum::PENDING->value)->count();
                        @endphp
                        @if($count)

                        @endif--}}
                        <livewire:admin.charge-count-component/>

                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.presints.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.presints.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>طلبات الوكالة</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.notifications.create')}}"
                       class="nav-link @if(request()->routeIs('dashboard.notifications.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>إرسال إشعار</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.settings.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.settings.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الإعدادات</p>
                    </a>
                </li>
                @endhasrole
                @hasanyrole('super_admin|partner')
                <li class="nav-header">الأساسي</li>
                <li class="nav-item">
                    <a href="{{route('dashboard.users.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.users.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>المستخدمين</p>
                    </a>
                </li>
                @endhasrole
                @hasrole('super_admin')
                <li class="nav-item">
                    <a href="{{route('dashboard.sliders.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.sliders.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الإعلانات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.groups.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.groups.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الفئات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.categories.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.categories.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الأقسام</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard.products.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.products.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>المنتجات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard.bills.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.bills.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الطلبات</p>
                    </a>
                </li>


                <li class="nav-header">أون لاين</li>
                <li class="nav-item">
                    <a href="{{route('dashboard.servers.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.servers.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>السيرفرات</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.countries.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.countries.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الدول</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route('dashboard.programs.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.programs.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>التطبيقات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard.orders.index')}}"
                       class="nav-link @if(request()->routeIs('dashboard.orders.*')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>جميع الطلبات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route('dashboard.count')}}"
                       class="nav-link @if(request()->routeIs('dashboard.count')) active @endif">
                        <i class="nav-icon fa fa-file"></i>
                        <p>الكمية في موقع جورج</p>
                    </a>
                </li>


                @endhasrole
                <li class="nav-header">تسجيل الخروج</li>
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                    <a href="#" class="nav-link"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-circle-o text-danger"></i>
                        <p class="text">تسجل الخروج</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</div>
