@extends('layouts.base')

@section('title')
    出错啦
@endsection

@section('header')
@endsection

@section('content')
    <div class="container">
        <div class="raw mt-5 pt-5 show text-left">
            <div class="col-12 alert alert-warning" role="alert">
                <i class="icon ion-ios-information-circle-outline"></i>
                {{$errorMsg}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('footer')
@endsection
