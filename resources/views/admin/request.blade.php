@extends('layouts.app')

@section('content')



    <div class="container">
    <table class="table">
        <thead>
        <tr>
            <th scope="col">id</th>
            <th scope="col">email</th>
            <th scope="col">pattern</th>
            <th scope="col">hash</th>
            <th scope="col">status</th>
            <th scope="col">sender</th>
            <th scope="col">date_send</th>
        </tr>
        </thead>
        <tbody>

@if($email_request)

@foreach($email_request as $val)
    <tr>
        <th scope="row">{{$val->id}}</th>
        <td>{{$val->email}}</td>
        <td>{{$val->pattern}}</td>
        <td>{{$val->hash}}</td>
        <td>{{$val->status}}</td>
        <td>{{$val->sender}}</td>
        <td>{{$val->date_send}}</td>
    </tr>

@endforeach
@endif

        </tbody>
    </table>
        {{$email_request->links()}}

    </div>
@endsection

