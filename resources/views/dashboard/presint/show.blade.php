@extends('dashboard.layouts.master')


@section('bread')
    <li class="breadcrumb-item ">الأسئلة</li>
    <li class="breadcrumb-item active">جميع الأسئلة</li>
@endsection


@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>السؤال</th>
                                <th>الإجابة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($presint->asks as $ask)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$ask->ask}}</td>
                                    <td>{{$ask->pivot->answer}}</td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
        </div>

    </div>

@endsection

