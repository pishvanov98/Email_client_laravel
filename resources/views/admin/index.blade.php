@extends('layouts.app')

@section('content')

    <div class="container flex_wrapper_admin">
        <a class="btn btn-primary " href="{{route('admin.view_create')}}" role="button">Добавить шаблон</a>
    </div>

    <div class="container">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Наименование шаблона</th>
            <th scope="col">Действие</th>
        </tr>
        </thead>
        <tbody>

    @if($view)
    @foreach($view as $val)
        <tr>
            <th scope="row">{{$val->id}}</th>
            <td>{{$val->name}}</td>
            <td><a href="#">Изменить</a></td>
        </tr>
    @endforeach


    @endif

@endsection
        </tbody>
    </table>
    </div>
@push('scripts')
<script src="{{asset('js/query-3.7.0.min.js')}}"></script>
@endpush
