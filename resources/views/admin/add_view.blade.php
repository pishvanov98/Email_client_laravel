@extends('layouts.app')

@section('content')
    <div class="container">
    <form action="{{route('admin.view_store')}}" method="post">
        @csrf
        <div class="mb-3">
            <label for="exampleInputNameView" class="form-label">Наименование шаблона</label>
            <input name="exampleInputNameView" type="text" class="form-control" id="exampleInputNameView">
        </div>
        <div class="mb-3">
        <select name="exampleInputNameStatus" class="form-select" aria-label="Default select example">
            <option selected value="1">Активный</option>
            <option value="2">Не активный</option>
        </select>
        </div>
        <div class="mb-3">
            <textarea name="exampleInputNameContent" id="exampleInputNameContent"></textarea>
        </div>
        <button  type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>

@endsection

@push('css')

    <link href="{{asset('plugins/summernote/summernote-bs4.min.css')}}" rel="stylesheet">

@endpush

@push('scripts')
    <script src="{{asset('js/query-3.7.0.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/lang/summernote-ru-RU.min.js')}}"></script>
    <script>

        $(function (){

            $('#exampleInputNameContent').summernote({
                lang: 'ru-RU',
                height: 200
            });

        });

    </script>

@endpush
