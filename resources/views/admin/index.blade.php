@extends('layouts.app')

@section('content')

    <div class="container">
        <a class="btn btn-primary" href="{{route('admin.view_create')}}" role="button">Добавить шаблон</a>
    </div>


@endsection



@push('scripts')
<script src="{{asset('js/query-3.7.0.min.js')}}"></script>
@endpush
