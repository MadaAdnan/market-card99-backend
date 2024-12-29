<li class="nav-item dropdown" wire:poll.20000ms="test">
    <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fa fa-bell-o"></i>
        <span class="badge badge-warning navbar-badge">{{$bills->count()}}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-left">
      @foreach($bills as $bill)
            <a href="{{route('dashboard.bills.index')}}" class="dropdown-item">
                <i class="fa fa-envelope ml-2"></i>{{$bill->user->name}}
                <span class="float-left text-muted text-sm">{{$bill->created_at->diffForHumans()}}</span>
            </a>
      @endforeach




        <a href="{{route('dashboard.bills.index')}}" class="dropdown-item dropdown-footer">عرض جميع الطلبات </a>
    </div>
</li>
