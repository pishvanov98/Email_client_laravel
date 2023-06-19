@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{route('admin.image.store')}}" enctype="multipart/form-data" method="post">
            @csrf
            <div class="input-group">
                <input type="file" class="form-control" name="image" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Загрузить</button>
            </div>
        </form>


        <div style="margin-top: 10px" class="wrapper_block_img gallery">

            @if($all_img)
                @foreach($all_img as $item)
                    <a rel="gallery" data-fancybox class="photo" href="{{asset($item->image_to_server)}}" title="{{$item->image_select}}"><img src="{{asset($item->image_to_server)}}" width="180" height="130" alt="" /></a>
                @endforeach

            @endif


        </div>


    </div>

@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endpush


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.4.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script>

        $(document).ready(function(){
            $("a.photo").fancybox({
                transitionIn: 'elastic',
                transitionOut: 'elastic',
                speedIn: 500,
                speedOut: 500,
                hideOnOverlayClick: false,
                titlePosition: 'over'
            });
        });

    </script>

@endpush
