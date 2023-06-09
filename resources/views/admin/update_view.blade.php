@extends('layouts.app')

@section('content')
    <div class="container">
    <form action="{{route('admin.view_update',$view->id)}}" method="post">
        @csrf
        @method('put')
        <div class="mb-3">
            <label for="exampleInputNameView" class="form-label">Наименование шаблона</label>
            <input name="exampleInputNameView" value="{{$view->name}}" type="text" class="form-control" id="exampleInputNameView">
        </div>
        <div class="mb-3">
        <select name="exampleInputNameStatus" class="form-select" aria-label="Default select example">
            @if($view->status == 1)
                <option selected value="1">Активный</option>
                <option value="2">Не активный</option>
            @else
                <option value="1">Активный</option>
                <option selected value="2">Не активный</option>
            @endif
        </select>
        </div>
        <div class="mb-3">
            <textarea name="exampleInputNameContent" id="exampleInputNameContent">{{$view->data}}</textarea>
        </div>
        <button  type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>

@endsection

@push('scripts')
    <script src="{{asset('assets/vendor/ckeditor/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'exampleInputNameContent',{height: 800} );

    </script>
@endpush
