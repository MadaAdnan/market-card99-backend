<div class="row">
    <div class="col-md-12">
        <ul wire:sortable="updateTaskOrder">
            @foreach ($products as $product)
                <li wire:sortable.item="{{ $product->id }}" wire:key="task-{{ $product->id }}" class="card card-body py-0">
                    <div class="d-flex flex-row justify-content-between justify-items-center"  wire:sortable.handle>
                        <h4>{{ $product->name }}</h4>
                        <div><i class="fa fa-hand-o-up"></i></div>
                    </div>

                </li>
            @endforeach
        </ul>
    </div>
</div>
